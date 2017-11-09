<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
//    use SoftDeletes;

    protected $fillable = [
        'title',
    ];

    public function tags()
	{
		return $this->belongsToMany(Tag::class, 'post_tag',  'post_id', 'tag_id');
	}

	public function setTags(array $tags, $postId)
	{
		// 获取原先的 tags
		$oldTags = Post::find($postId)->tags()->get()->pluck('name');

		// 需要删除的 tag
		$oldTags->filter(function ($tag) use ($tags) {
			return !in_array($tag, $tags);
		})->each(function ($tag) use ($postId) {
			$tagId = Tag::where('name', $tag)->first(['id'])->id;
			PostTag::where(['tag_id' => $tagId, 'post_id' => $postId])->delete();
		});

		// 需要新增的
		collect($tags)->reject(function ($tag) use ($oldTags) {
			return in_array($tag, $oldTags->toArray());
		})->each(function ($tag) use($postId) {
			$tagId = Tag::where('name', $tag)->first(['id'])->id;
			PostTag::create([
				'post_id' => $postId,
				'tag_id'  => $tagId
			]);
		});
	}
}
