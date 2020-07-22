@extends('layouts.app')

@section('content')
<main class="flex flex-col sm:flex-row w-full">
    <div class="p-4 flex-grow">
        @forelse($articles as $article)
        <article class="prose">
            <div class="flex items-baseline">
                <h3 class="">
                    <a href="/articles/{{ $article->slug }}">{{ $article->title }}</a>
                </h3>
                <span class="ml-2 text-xs italic">{{ optional($article->published_at)->diffForHumans() }} door <a href="/users/{{ $article->author->id }}">{{ $article->author->name }}</a></span>
            </div>
            <div class="flex space-x-1">
                @foreach ($article->locations as $location)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium leading-4 bg-indigo-100 text-indigo-800">{{ $location->title }}</span>
                @endforeach
            </div>
            <div class="">
                @markdown($article->excerpt)
            </div>
        </article>
        @empty
        <article class="mt-6 prose">
            @markdown('### Nog geen blogs.')

            @auth
                @markdown('[Blog schrijven](/articles/create)')
            @endauth
        </article>
        @endforelse
    </div>
    <div class="p-4 w-full sm:w-64">
        @auth
            <a href="/articles/create">Blog schrijven</a>
        @endauth
        <aside>lijst met tags?</aside>
        <aside>kaart-overzicht?</aside>
        <aside>kalender-overzicht?</aside>
    </div>
</main>
<nav>
    {{ $articles->onEachSide(1)->links() }}
</nav>
@endsection
