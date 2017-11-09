<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	/*
	 * 保存之前不存在的 tag
	 */
    public function setTags($names)
	{
		foreach ($names as $name) {
			Tag::firstOrCreate(['name' => $name]);
		}
	}
}
