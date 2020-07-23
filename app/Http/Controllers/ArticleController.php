<?php

namespace App\Http\Controllers;

use App\Article;
use App\PublishedArticle;
use Illuminate\Http\Request;
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
    public function index()
    {
        $articles = PublishedArticle::latest('published_at')
            ->with('author:id,name')
            ->paginate();
        $locations = $articles->pluck('locations')
            ->flatten()
            ->unique('id')
            ->values();

        return view('articles.index', compact('articles', 'locations'));
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

        $taggables = collect($article->locations)
            ->push(...$article->tags)
            ->sortBy(function ($taggable) {
                return Str::lower($taggable->title);
            });

        return view('articles.show', compact('article', 'taggables'));
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
