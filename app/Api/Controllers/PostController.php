<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Post;
use App\PostTag;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

	protected $tagModel;
	protected $postModel;

	public function __construct(Tag $tagModel, Post $postModel)
	{
		$this->tagModel = $tagModel;
		$this->postModel = $postModel;
	}

	/**
	 * 保存文章接口
	 *
	 * @param PostCreateRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 * @author qian.zhao
	 */
    public function save(PostCreateRequest $request)
    {
		$content = $request->get('content');

		// 解析出标题 和 标签
		$head = explode(PHP_EOL, array_first(explode('---', $content)));
		$title = trim(str_replace('#', '', array_first($head)));

		array_shift($head);
		$tags = collect($head)->reject(function ($tag) {
			return empty($tag);
		})->map(function ($tag) {
			return str_replace('- ', '', $tag);
		});

		// 检查文件名是否已经存在
		$filename = $this->getFilename($title);
		if (Storage::exists($filename)) {
			throw new \Exception('文章已存在');
		}

        Storage::put($filename, $content);

        if ($post = Post::create(['title' => $title])) {
            $postId = $post->id;
			$this->tagModel->setTags($tags);
			$this->postModel->setTags($tags->toArray(), $postId);
            return response()->json(compact('postId'));
        }
    }

	/**
	 * 保存临时页面的内容
	 *
	 * @param Request $request
	 * @return bool
	 * @author qian.zhao
	 */
    public function saveMessage(Request $request)
	{
		// 存储内容
		$content = $request->get('content');

		Cache::store('redis')->put('message_content', $content, 60 * 24);

		return 'ok';
	}

    public function index()
    {
        $posts = [];

        $postRaw = Post::orderBy('created_at', 'desc')->get()->reject(function ($post) {
            return $post->title == '关于我';
        });

        //transform
        foreach ($postRaw as $key => $value) {
            $posts[$key]['id'] = $value['id'];
            $posts[$key]['title'] = $value['title'];
            $posts[$key]['date'] = Carbon::parse($value['created_at'])->toDateString();
        }

        return response()->json(['data' => $posts]);
    }

	/**
	 * 根据标签获取文章列表
	 *
	 * @param $tagId
	 * @return \Illuminate\Http\JsonResponse
	 * @author zhaohehe
	 */
    public function tag($tagId)
	{
		$posts = [];

		$postIds = PostTag::where('tag_id', $tagId)->pluck('post_id')->toArray();

		$postRaw = Post::whereIn('id', $postIds)->orderBy('created_at', 'desc')->get()->reject(function ($post) {
			return $post->title == '关于我';
		});

		//transform
		foreach ($postRaw as $key => $value) {
			$posts[$key]['id'] = $value['id'];
			$posts[$key]['title'] = $value['title'];
			$posts[$key]['date'] = Carbon::parse($value['created_at'])->toDateString();
		}

		return response()->json(['data' => $posts]);
	}

	/**
	 * 获取一个文字的内容
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 * @author qian.zhao
	 */
    public function show($id)
    {
        $postRaw = Post::find($id);

        //transform
        $contentRaw = Storage::get($this->getFilename($postRaw['title']));

        // 只返回文章的内容部分
		$cutoffLine = strpos($contentRaw, '---');
		$startPos = $cutoffLine + 3;
		$content = substr($contentRaw, $startPos);
		$content = empty($content) ? '...' : $content;

		$post = [
			'id' => $postRaw['id'],
			'title' => $postRaw['title'],
			'content' => $content,

			// 按照标签长度排序
			'tags' => array_values($postRaw->tags->sortBy(function ($tag) {
				return strlen($tag->name);
			})->toArray()),

			'contentRaw' => $contentRaw,
			'date' => Carbon::parse($postRaw['created_at'])->toDateString(),
		];

        return response()->json(['data' => $post]);
    }

    public function update($id, PostUpdateRequest $request)
    {
        $post = Post::find($id);
		$content = $request->get('content');

		// 解析出标题 和 标签
		$head = explode(PHP_EOL, array_first(explode('---', $content)));
		$title = trim(str_replace('#', '', array_first($head)));

		array_shift($head);
		$tags = collect($head)->reject(function ($tag) {
			return empty($tag);
		})->map(function ($tag) {
			return str_replace('- ', '', $tag);
		});

		$this->tagModel->setTags($tags);
		$this->postModel->setTags($tags->toArray(), $id);

		$filename = $this->getFilename($post['title']);

        //save new file
        Storage::put($filename, $content);

        //update
        if ($post = $post->update(['title' => $title])) {
            return response()->json(['data' => ['postId' => $id]]);
        }


    }

    public function destroy($id)
    {
        if (Post::destroy($id)) {
            return response()->json();
        }
    }

}