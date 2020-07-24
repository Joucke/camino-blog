<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
    public function store(Request $request)
    {
        Tag::create($request->only('title'));
        return Tag::orderBy('title')->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $tag->update($request->only('title'));
        if ($request->has('pivot')) {
            $tag->pivot = $request->input('pivot');
        }
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
