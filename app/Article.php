<?php

namespace App;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
        $this->loadMissing('locations', 'tags');

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

    public function scopeForIndex(Builder $query)
    {
        return $query->latest('published_at')
            ->with('author', 'tags', 'locations')
            ->paginate();
    }

    public function scopePublishedIn(Builder $query, $year = null, $month = null)
    {
        if ($year && $month) {
            return $query->where('published_at', 'like', "{$year}-{$month}-%");
        }
        return $query;
    }

    public static function history()
    {
        return DB::table('articles')->whereNotNull('published_at')->select(DB::raw('count(*) articles_count, DATE_FORMAT(`published_at`, "%y-%m-01") published_month'))->groupBy('published_month')->orderBy('published_month', 'desc')->get()->map(function ($item) {
                $item->published_month = Carbon::create($item->published_month);
                $item->url = $item->published_month->format('/Y/m');
                return $item;
            })->groupBy(function ($item) {
            return $item->published_month->format('Y');
        });
    }
}
