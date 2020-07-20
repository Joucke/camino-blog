<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PublishedArticle extends Article
{
    protected $table = 'articles';

    protected static function booted()
    {
        if (auth()->check()) {
            return;
        }
        static::addGlobalScope('published', function (Builder $builder) {
            $builder->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        });

    }
}
