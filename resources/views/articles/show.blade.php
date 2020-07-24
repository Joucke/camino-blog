@extends('layouts.app')

@section('content')
<main class="flex flex-col sm:flex-row w-full">
    <div class="p-4 flex-grow">
        <h1 class="text-2xl font-bold mb-3">{{ $article->title }}</h1>
        <em class="text-xs">
            {{ $article->author->name }}, {{ optional($article->published_at)->diffForHumans() ?? 'nog niet gepubliceerd'}}</em>
        <div class="flex flex-wrap">
            @foreach ($article->taggables as $tag)
                <span class="mr-1 mt-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium leading-4 bg-indigo-100 text-indigo-800">{{ $tag->title }}</span>
            @endforeach
        </div>
        <div class="prose mt-6">
@markdown
{{ $article->body }}
@endmarkdown
        </div>
    </div>
    <div class="p-4 w-full sm:w-64">
        <aside>
            <article-map
                class="w-full h-64"
                :locations="{{ $article->locations }}"
            ></article-map>
        </aside>
        @auth
            <a href="/articles/{{ $article->slug }}/edit">Bewerken</a>
            <form action="/articles/{{ $article->slug }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Verwijderen</button>
            </form>
        @endauth
    </div>
</main>
<portal-target name="manage-tag-modal">
</portal-target>
<portal-target name="manage-location-modal">
</portal-target>
@endsection
