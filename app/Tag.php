<?php

namespace App;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
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
            'article_tag',
            'tag_id',
            'article_id',
        );
    }

    public function getUrlAttribute()
    {
        return '/tags/' . $this->slug;
    }
}
