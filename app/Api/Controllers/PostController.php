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
    public function __construct()
    {

    }

    public function create(PostCreateRequest $request)
    {
        //save post content to file
        Storage::put($request['title'].'.md', $request['content']);

        //db
        if ($post = Post::create(['title' => $request['title']])) {
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

    public function show($id)
    {
        $postRaw = Post::find($id);

        //transform
        $post['id'] = $postRaw['id'];
        $post['title'] = $postRaw['title'];
        $post['date'] = Carbon::parse($postRaw['created_at'])->toDateString();
        $post['content'] = Storage::get($postRaw['title'].'.md');

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
}