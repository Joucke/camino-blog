<?php

namespace App;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasSlug;

    protected $guarded = [];

    protected $appends = [
        'url',
    ];

    public function articles()
    {
        return $this->belongsToMany(
            PublishedArticle::class,
            'article_location',
            'location_id',
            'article_id',
        );
    }

    public function getUrlAttribute()
    {
        return '/locations/' . $this->slug;
    }
}
