<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

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
            return response()->json(compact('postId'));
        }
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
			'date' => Carbon::parse($postRaw['created_at'])->toDateString(),
		];

        return response()->json(['data' => $post]);
    }

    public function update($id, PostUpdateRequest $request)
    {
        $post = Post::find($id);

        //delete old file
        Storage::delete($post['title'].'.md');

        //save new file
        Storage::put($request['title'].'.md', $request['content']);

        //update
        if ($post = $post->update(['title' => $request['title']])) {
            return response()->json(['data' => ['postId' => $id]]);
        }


    }

    public function destroy($id)
    {
        if (Post::destroy($id)) {
            return response()->json();
        }
    }

    private function getFilename($title)
	{
		return $title . '-' . date('Y-m-d') . '.md';
	}
}