<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use ErrorTrait;

    static function boot()
    {
    	parent::boot();

    	Static::observe(new BaseObserver);
    }


}
