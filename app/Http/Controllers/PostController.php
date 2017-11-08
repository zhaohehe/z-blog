<?php

namespace App\Http\Controllers;

use App\Post;
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

    public function create()
    {
        return view('admin.post.create');
    }

    public function edit($id)
    {
        $postRaw = Post::find($id);

        //transform
        $post['id'] = $postRaw['id'];
        $post['title'] = $postRaw['title'];
        $post['content'] = Storage::get($postRaw['title'].'.md');

        return view('admin.post.edit')->with(compact('post'));
    }

    public function me()
    {
        $postId = Post::where('title', '关于我')->first()->id;

        return view('blog.post')->with(['id' => $postId, 'blogTitle' => env('BLOG_TITLE')]);
    }
}
