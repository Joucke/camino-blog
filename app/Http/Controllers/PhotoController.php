<?php

namespace App\Http\Controllers;

use App\Article;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        foreach ($request->file('photos') as $photo) {
            $path = $photo->store(now()->format('Y-m-d'), 'photos');
            Photo::create(compact('path'));
        }
        return Photo::latest()->get();
    }

    public function index()
    {
        return Photo::latest()->get();
    }

    public function destroy(Request $request, Photo $photo)
    {
        // TODO: show deleter in which article(s) the photo is used.
        abort_if(Article::where('body', 'like', "%(/images/{$photo->path})%")->count(), 403);

        $photo->delete();

        if ($request->wantsJson()) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        return redirect('/');
    }
}
