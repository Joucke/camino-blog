<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $guarded = [];
    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function (Article $article) {
            $article->slug = Str::slug($article->title);
        });
    }

    public function getExcerptAttribute()
    {
        $firstParagraph = Str::before($this->body, "\n");
        return Str::substr($firstParagraph, 0, 200);
    }

    public function getTitleMarkdownAttribute()
    {
        return sprintf(
            '### [%s](/articles/%s)',
            $this->title,
            $this->slug
        );
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
