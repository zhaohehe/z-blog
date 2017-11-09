<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('blog.home')->with(['blogTitle' => env('BLOG_TITLE')]);
    }

    public function show($id)
    {
        return view('blog.post')->with(['id' => $id, 'blogTitle' => env('BLOG_TITLE')]);
    }

    public function tag($tag)
	{
		return view('blog.tag')->with(['tag' => $tag, 'blogTitle' => env('BLOG_TITLE')]);

	}

	/**
	 * post create page
	 *
	 * @return $this
	 * @author zhaohehe
	 */
    public function create()
    {
    	$defaultContent = '## title\n- tag1\n- tag2\n---';

        return view('admin.post.create')->with([
        	'defaultContent' => $defaultContent
		]);
    }

	/**
	 * 编辑文章页面
	 *
	 * @param $id
	 * @return $this
	 * @ahthor zhaohehe
	 */
    public function edit($id)
    {
        $postRaw = Post::find($id);

        //transform
        $post['id'] = $postRaw['id'];
        $post['title'] = $postRaw['title'];
        try {
			$content = Storage::get($this->getFilename($postRaw['title']));
		} catch (\Exception $e) {
        	Log::error($e->getMessage());
        	$content = '...';
		}
		$post['content'] = str_replace(PHP_EOL, '\r\n', $content);

        return view('admin.post.edit')->with(compact('post'));
    }

    public function me()
    {
        $postId = Post::where('title', '关于我')->first()->id;

        return view('blog.post')->with(['id' => $postId, 'blogTitle' => env('BLOG_TITLE')]);
    }


    public function message()
	{
		// 获取内容
		$content = Cache::store('redis')->get('message_content');

		return view('message')->with([
			'content' => $content
		]);
	}
}
