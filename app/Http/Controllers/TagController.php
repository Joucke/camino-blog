<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTag;
use App\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tag::orderBy('title')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTag $request)
    {
        Tag::create($request->validated());
        return Tag::orderBy('title')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Tag $tag)
    {
        $articles = $tag->articles()->forIndex();
        return (new ArticleController)->index($request, $articles, $tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTag $request, Tag $tag)
    {
        $tag->update($request->validated());
        if ($request->has('pivot')) {
            $tag->pivot = $request->input('pivot');
        }
        $tag->articles_count = $tag->articles()->count();
        return $tag;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tag $tag)
    {
        if ($tag->articles->isEmpty()) {
            $tag->delete();
            return Tag::all();
        }
        return new JsonResponse([
            // TODO: move to translation file
            'error' => 'Een tag kan alleen verwijderd worden wanneer deze niet aan een of meer blogs is gekoppeld.',
        ], 422);
    }
}
