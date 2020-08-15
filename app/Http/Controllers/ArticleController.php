<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests\StoreArticle;
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
    public function index(Request $request, ?LengthAwarePaginator $articles = null, ?Model $parent = null, ?string $year = null, ?string $month = null)
    {
        $articles = $articles ?? PublishedArticle::publishedIn($year, $month)->forIndex();

        $locations = $articles->pluck('locations')
            ->flatten()
            ->unique('id')
            ->values();

        $tags = Tag::withCount('articles')->orderBy('articles_count', 'desc')->orderBy('title')->get();

        $history = Article::history();

        if (!$parent && $year && $month) {
            $date = Carbon::create(sprintf('%s-%s-01', $year, $month))->isoFormat('MMMM Y');
            $parent = (object)['title' => ucfirst($date)];
        }
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
     * @param  \App\Http\Requests\StoreArticle  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticle $request)
    {
        $request->store();

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

        $history = Article::history();

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
    public function update(StoreArticle $request, Article $article)
    {
        $article = $request->update($article);

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
