@extends('layouts.app')

@section('content')
<main class="flex flex-col sm:flex-row w-full">
    <div class="p-4 flex-grow">
        <div class="prose">
@markdown
## {{ $article->title }}
_{{ $article->author->name }}, {{ optional($article->published_at)->diffForHumans() }}_

{{ $article->body }}
@endmarkdown
        </div>
    </div>
    <div class="p-4 w-full sm:w-64">
        @auth
            <a href="/articles/{{ $article->slug }}/edit">Bewerken</a>
        @endauth
    </div>
</main>
@endsection
