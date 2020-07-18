@extends('layouts.app')

@section('content')
<main class="flex flex-col sm:flex-row w-full">
    <div class="p-4 flex-grow">
        @forelse($articles as $article)
        <article class="mt-6 prose">
            <div class="flex items-baseline">
                @markdown($article->title_markdown)
                <span class="ml-2 text-xs italic">{{ $article->published_at->diffForHumans() }} by <a href="/users/{{ $article->author->id }}">{{ $article->author->name }}</a></span>
            </div>
            @markdown($article->excerpt)
        </article>
        @empty
        <article class="mt-6 prose">
            @markdown('### No articles yet.')

            @auth
                @markdown('[Create an article](/articles/create)')
            @endauth
        </article>
        @endforelse
    </div>
    <div class="p-4 w-full sm:w-64">
        @auth
            <a href="/articles/create">Add an article</a>
        @endauth
        <aside>list of tags?</aside>
        <aside>map view</aside>
        <aside>calendar view</aside>
    </div>
</main>
<nav>
    {{ $articles->onEachSide(1)->links() }}
</nav>
@endsection
