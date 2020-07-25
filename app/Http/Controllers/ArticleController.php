<?php

namespace App\Http\Controllers;

use App\Article;
use App\PublishedArticle;
use App\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ?LengthAwarePaginator $articles = null, ?Model $parent = null)
    {
        $articles = $articles ?? PublishedArticle::forIndex();

        $locations = $articles->pluck('locations')
            ->flatten()
            ->unique('id')
            ->values();

        $tags = Tag::withCount('articles')->orderBy('articles_count', 'desc')->orderBy('title')->get();

        $history = DB::table('articles')->whereNotNull('published_at')->select(DB::raw('count(*) articles_count, DATE_FORMAT(`published_at`, "%y-%m-01") published_month'))->groupBy('published_month')->orderBy('published_month', 'desc')->get()->map(function ($item) {
                $item->published_month = Carbon::create($item->published_month);
                return $item;
            })->groupBy(function ($item) {
            return $item->published_month->format('Y');
        });

        return view('articles.index', compact('articles', 'locations', 'parent', 'tags', 'history'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create', [
            'article' => new Article(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->only(['title', 'body', 'published_at']);
        $attributes['slug'] = Str::slug($attributes['title']);
        $article = auth()->user()->articles()->create($attributes);

        if ($request->has('locations')) {
            $locations = $request->input('locations');
            $article->locations()->attach($locations);
        }

        if ($request->has('tags')) {
            $tags = $request->input('tags');
            $article->tags()->attach($tags);
        }
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PublishedArticle  $article
     *
     * @return \Illuminate\Http\Response
     */
    public function show(PublishedArticle $article)
    {
        $article->load('locations', 'tags');
        $article->locations->sortBy('title');
        $article->tags->sortBy('title');


        $tags = Tag::withCount('articles')->orderBy('articles_count', 'desc')->orderBy('title')->get();

        $history = DB::table('articles')->whereNotNull('published_at')->select(DB::raw('count(*) articles_count, DATE_FORMAT(`published_at`, "%y-%m-01") published_month'))->groupBy('published_month')->orderBy('published_month', 'desc')->get()->map(function ($item) {
                $item->published_month = Carbon::create($item->published_month);
                return $item;
            })->groupBy(function ($item) {
            return $item->published_month->format('Y');
        });

        return view('articles.show', compact('article', 'tags', 'history'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        return view('articles.edit', [
            'article' => $article,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $attributes = $request->only(['title', 'body', 'published_at']);
        $attributes['slug'] = Str::slug($attributes['title']);
        $article->update($attributes);

        if ($request->has('locations')) {
            $locations = $request->input('locations');
            $article->locations()->sync($locations);
        }

        if ($request->has('tags')) {
            $tags = $request->input('tags');
            $article->tags()->sync($tags);
        }

        return redirect('/articles/'.$article->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return redirect('/');
    }
}
