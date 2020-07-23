<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = [];

    public function articles()
    {
        return $this->belongsToMany(
            PublishedArticle::class,
            'article_location',
            'location_id',
            'article_id',
        );
    }
}
