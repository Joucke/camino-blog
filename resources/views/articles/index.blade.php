@extends('layouts.app')

@section('content')
<main class="flex flex-col sm:flex-row w-full">
    <div class="p-4 flex-grow">
        @forelse($articles as $article)
        <article class="mt-6 prose">
            <div class="flex items-baseline">
                @markdown($article->title_markdown)
                <span class="ml-2 text-xs italic">{{ $article->published_at->diffForHumans() }} door <a href="/users/{{ $article->author->id }}">{{ $article->author->name }}</a></span>
            </div>
            @markdown($article->excerpt)
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
