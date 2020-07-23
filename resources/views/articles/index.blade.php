@extends('layouts.app')

@section('content')
<main class="flex flex-col sm:flex-row w-full">
    <div class="p-4 flex-grow">
        @forelse($articles as $article)
        <article class="{{ $loop->first ? '' : 'mt-3 border-t pt-3' }}">
            <div class="flex flex-col sm:flex-row sm:items-baseline">
                <h3 class="text-lg tracking-wide font-bold">
                    <a class="underline" href="/articles/{{ $article->slug }}">{{ $article->title }}</a>
                </h3>
                <span class="text-sm italic sm:ml-3 sm:text-xs">{{ optional($article->published_at)->diffForHumans() }} door <a class="underline" href="/users/{{ $article->author->id }}">{{ $article->author->name }}</a></span>
            </div>
            <div class="flex flex-wrap">
                @foreach ($article->locations as $location)
                    <span class="mt-1 mr-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium leading-4 bg-cool-gray-100 text-cool-gray-600">{{ $location->title }}</span>
                @endforeach
            </div>
            <div class="mt-3">
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
    <div class="p-4 w-full sm:w-64 flex-shrink-0">
        <aside class="mt-6">
            <article-map
                class="w-full h-64"
                :locations="{{ $locations }}">
            </article-map>
        </aside>
        @auth
        <aside class="mt-6">
            <a href="/articles/create">Blog schrijven</a>
        </aside>
        @endauth
        <aside>lijst met tags?</aside>
        <aside>kalender-overzicht?</aside>
    </div>
</main>
<nav>
    {{ $articles->onEachSide(1)->links() }}
</nav>
@endsection
