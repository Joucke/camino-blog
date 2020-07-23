<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    public function articles()
    {
        return $this->belongsToMany(
            PublishedArticle::class,
            'article_tag',
            'tag_id',
            'article_id',
        );
    }
}
