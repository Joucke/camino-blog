@extends('layouts.app')

@section('content')
<main class="flex flex-col sm:flex-row w-full">
    <div class="p-4 flex-grow">
        <nav class="flex items-center text-sm leading-5 font-medium">
            @if($parent)
            <a href="/" class="text-blue-500 hover:text-blue-700 hover:underline transition duration-150 ease-in-out">Alle blogs</a>
            <svg class="flex-shrink-0 mx-2 h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-blue-700">{{ $parent->title ?? $parent->name }}</span>
            @else
            <span class="text-blue-700">Alle blogs</span>
            @endif
        </nav>
        @forelse($articles as $article)
        <article class="mt-3 border-t pt-3">
            <div class="flex flex-col sm:flex-row sm:items-baseline">
                <h3 class="text-2xl tracking-wide font-bold">
                    <a class="" href="/articles/{{ $article->slug }}">{{ $article->title }}</a>
                </h3>
                <span class="text-sm italic sm:ml-3 sm:text-xs">{{ optional($article->published_at)->diffForHumans() }} door <a class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium leading-4 bg-blue-100" href="/users/{{ $article->author->id }}">{{ $article->author->name }}</a></span>
            </div>
            <div class="flex flex-wrap">
            @foreach ($article->taggables as $tag)
                <taggable-manager :tag="{{ $tag }}">
                    <template v-slot:default="{taggable}">
                        <a :href="taggable.url" class="mt-1 mr-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium opacity-50 leading-4 bg-blue-800 text-yellow-200">@{{ taggable.title }}</a>
                    </template>
                </taggable-manager>
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
        <aside class="">
            <article-map
                class="w-full h-64"
                :locations="{{ $locations }}">
            </article-map>
        </aside>
        <aside class="mt-6">
            <h4>Tags</h4>
            <div>
            @foreach ($tags as $tag)
                <taggable-manager :tag="{{ $tag }}">
                    <template v-slot:default="{taggable}">
                        <a :href="taggable.url" class="mt-1 mr-1 inline-flex items-center text-xs font-medium leading-4">@{{ taggable.title }}@{{ taggable.articles_count ? ` (${taggable.articles_count})` : '' }}</a>
                    </template>
                </taggable-manager>
            @endforeach
            </div>
        </aside>
        <aside class="mt-6">
            @foreach ($history as $year => $months)
            <h4 class="mt-2">{{ $year }}</h4>
            @foreach ($months as $month)
            <a href="" class="block text-xs capitalize">{{ $month->published_month->isoFormat('MMMM') }} ({{ $month->articles_count }})</a>
            @endforeach
            @endforeach
        </aside>
        @auth
        <aside class="mt-6">
            <a href="/articles/create" class="inline-flex w-full justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-yellow-200 bg-blue-800 hover:bg-blue-900 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-900 transition duration-150 ease-in-out">Blog schrijven</a>
        </aside>
        @endauth
    </div>
</main>
<nav>
    {{ $articles->onEachSide(1)->links() }}
</nav>
<portal-target name="manage-tag-modal">
</portal-target>
<portal-target name="manage-location-modal">
</portal-target>
@endsection
