<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Http\Request;

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
}
