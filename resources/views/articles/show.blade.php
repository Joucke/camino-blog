@extends('layouts.app')

@section('content')
<main class="flex flex-col sm:flex-row w-full">
    <div class="p-4 flex-grow">
        <div class="prose">
            <h1>{{ $article->title }}</h1>
            <em class="text-sm">
                {{ $article->author->name }}, {{ optional($article->published_at)->diffForHumans() ?? 'nog niet gepubliceerd'}}</em>
            <div class="flex space-x-1">
                @foreach ($article->locations as $location)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium leading-4 bg-indigo-100 text-indigo-800">{{ $location->title }}</span>
                @endforeach
            </div>
@markdown
{{ $article->body }}
@endmarkdown
        </div>
    </div>
    <div class="p-4 w-full sm:w-64">
        @auth
            <a href="/articles/{{ $article->slug }}/edit">Bewerken</a>
        @endauth
        <article-map
            class="w-full h-64"
            :locations="{{ $article->locations }}"
        />
    </div>
</main>
@endsection
