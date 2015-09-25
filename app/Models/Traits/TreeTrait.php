<?php 

namespace App;

use Illuminate\Support\MessageBag;
use Validator;

trait TreeTrait {

	static function bootTreeTrait()
	{
		Static::saving(function($model){
			if (!$model->getNameField())
			{
				throw new Exception('Please set $name_field', 1);
			}

			if (!$model->getPathField())
			{
				throw new Exception('Please set $path_field', 1);
			}
			else
			{
				$model->{$model->getPathField()} = '';
			}


			$new_instance = $model->newInstance();

			// RULES
			$rules['parent_id']				= ['integer', 'min:0'];
			if ($model->parent_id)
			{
				$rules['parent_id'][] = 'exists:' . $model->getTable() . ',id';
			}

			$validator = Validator::make($model->toArray(), $rules);
			if ($validator->fails())
			{
				$model->setErrors($validator->messages());
				return false;
			}
			else 
			{
				if ($model->parent_id)
				{
					$parent = $new_instance->findorfail($model->parent_id);

					$model->{$model->getPathField()}  = $parent->{$model->getPathField()};
					$model->{$model->getPathField()} = ($model->{$model->getPathField()} ? $model->{$model->getPathField()} . $model->getDelimiter() : '') . str_slug($model->{$model->getNameField()});
				}
				else
				{
					$model->{$model->getPathField()} = str_slug($model->{$model->getNameField()});
				}

				// Check Duplicated path
				$rules['path'] = ['unique:' . $new_instance->getTable() . ',path,' . ($model->id ? $model->id : 'NULL') . ',id'];
				$validator = Validator::make(['path' => $model->path], $rules, ['unique' => 'Data ' . $model->{$model->getNameField()} . ' already exists']);
				if ($validator->fails())
				{
					$model->setErrors($validator->messages());
					return false;
				}
			}
		});

		Static::updated(function($model) {
			if (!str_is($model->{$model->getPathField()}, $model->getOriginal($model->getPathField())))
			{
				foreach ($model->children as $x)
				{
					$x->save();
				}
			}
		});

		Static::deleting(function($model){
			if ($model->children->count())
			{
				$model->setErrors('Fail to delete ' . $model->{$model->getNameField()} . ' as it has subtrees');
				return false;
			}
		});
	}

	// ----------------------------------------------------------------------
	// RELATIONSHIP
	// ----------------------------------------------------------------------
	public function children()
	{
		return $this->hasMany(get_class($this), 'parent_id');
	}

	public function parent()
	{
		return $this->belongsTo(get_class($this), 'parent_id');
	}

	// ----------------------------------------------------------------------
	// SCOPE
	// ----------------------------------------------------------------------
	public function scopePathLike($q, $v = null)
	{
		if (!$v)
		{
			return $q;
		}
		else
		{
			return $q->where($this->getPathField(), 'like', str_replace('*', '%', $v));
		}	
	}

	public function scopeWithSubtreeById($q, $v = null)
	{
		if (!$v)
		{
			return $q;
		}
		else
		{
			$root = Static::find($v);
			if ($root->id)
			{
				return $q->pathLike($root->{Static::getPathField()} . $root->{Static::getPathField()} . '*');
			}
			else
			{
				return $q;
			}
		}	
	}


	public function scopeExceptSubtreeById($q, $v = null)
	{
		if (!$v)
		{
			return $q;
		}
		else
		{
			$subtree = Static::find($v);

			if ($subtree->id)
			{
				return $q->where($this->getPathField(), 'not like', $subtree->path . '%');
			}
			else
			{
				return $q;
			}

		}	
	}

	public function scopeExceptIds($q, $v = null)
	{
		if (!$v)
		{
			return $q;
		}
		else
		{
			if (!is_array($v))
			{
				$v = [$v];
			}
			return $q->whereNotIn('id', $v);
		}	
	}

	public function scopeFindPath($q, $v = null)
	{
		if (!$v)
		{
			return $q;
		}
		else
		{
			$v = str_replace("*", "%", $v);
			$v = str_replace(" ", "-", $v);
			return $q->where($this->getPathField(), 'like', $v);
		}
	}

	// ----------------------------------------------------------------------
	// ACCESSOR
	// ----------------------------------------------------------------------
	function getDescendantAttribute()
	{
		return Static::where($this->getPathField(), 'not like', $this->attributes[$this->getPathField()] . Static::getDelimiter() . '%');
	}

	function getPathAttribute()
	{
		return str_replace('-', ' ', str_replace(Static::getDelimiter(), ' > ', $this->attributes[$this->getPathField()]));
	}

	function getPathSlugAttribute()
	{
		return str_replace(Static::getDelimiter(), ',', $this->path);
	}

	function getLevelAttribute()
	{
		return count(explode($this->getDelimiter(), $this->path));
	}

	// ----------------------------------------------------------------------
	// FUNCTION
	// ----------------------------------------------------------------------
	static function getPathField()
	{
		return Static::$path_field ? Static::$path_field : 'path';
	}

	static function getNameField()
	{
		return Static::$name_field ? Static::$name_field : 'name';
	}

	static function getDelimiter()
	{
		return isset(Static::$delimiter) ? Static::$delimiter : ';;;';
	}
}
