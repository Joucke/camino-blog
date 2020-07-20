<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $paths = [];
        foreach ($request->file('photos') as $photo) {
            $paths[] = $photo->store(now()->format('Y-m-d'), 'photos');
        }
        return $paths;
    }
}
