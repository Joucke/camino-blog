<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $articles = $user->articles()->forIndex();
        return (new ArticleController)->index($request, $articles, $user);
    }
}
