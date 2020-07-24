<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function bootHasSlug()
    {
        static::saving(function (Model $model) {
            $model->slug = Str::slug($model->title);
        });
    }
}
