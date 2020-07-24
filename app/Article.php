<?php

namespace App;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasSlug;

    protected $guarded = [];
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getExcerptAttribute()
    {
        $firstParagraph = Str::before($this->body, "\n");
        return Str::substr($firstParagraph, 0, 200);
    }

    public function getTaggablesAttribute()
    {
        $this->load('locations', 'tags');

        return collect($this->locations)
            ->push(...$this->tags)
            ->sortBy(function ($taggable) {
                return Str::lower($taggable->title);
            });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function locations()
    {
        return $this->belongsToMany(
            Location::class,
            'article_location',
            'article_id'
        );
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'article_tag',
            'article_id',
        );
    }

    public function publish(?Carbon $when = null)
    {
        if (! $when) {
            $when = now();
        }
        $this->update(['published_at' => $when]);
    }
}
