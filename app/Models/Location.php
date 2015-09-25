<?php

namespace App;

class Location extends BaseModel
{
	use TreeTrait;

	protected $table 	= 'locations';
	protected $fillable = ['name', 'level', 'longitude', 'latitude'];
	protected $dates 	= ['created_at', 'updated_at'];

	static protected $name_field = 'name';
	static protected $path_field = 'path';

	static function boot()
	{
		parent::boot();
	}

	function scopeLevel($q, $v = null)
	{
		if (!$v)
		{
			return $q;
		}
		else
		{
			return $q->where('level', 'like', $v);
		}
	}

}
