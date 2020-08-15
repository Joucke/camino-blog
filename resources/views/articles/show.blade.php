@extends('layouts.app')

@section('content')
<main class="container mx-auto flex flex-col sm:flex-row">
    <div class="p-4 sm:pr-0 flex-grow">
        <nav class="flex items-center justify-between text-sm leading-5 font-medium">
            <div class="w-full flex flex-grow-0 items-center">
                <a href="/" class="text-blue-700 hover:text-blue-900 hover:underline transition duration-150 ease-in-out">Alle blogs</a>
                <svg class="flex-shrink-0 mx-2 h-5 w-5 text-blue-700" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-blue-900">{{ $article->title }}</span>
            </div>
            @auth
            <a href="/articles/{{ $article->slug }}/edit" title="Bewerken" class="flex w-6 h-6 items-center justify-center rounded font-bold text-lg bg-blue-800 text-yellow-200 p-1">
                <svg class="w-4 h-4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </a>
            @endauth
        </nav>
        <image-article class="mt-3 border-t pt-3">
            <div class="flex flex-col lg:flex-row lg:items-baseline lg:justify-between">
                <h1 class="text-2xl tracking-wide font-bold">{{ $article->title }}</h1>
                <em class="lg:mt-3 text-xs">
                    <a class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium leading-4 bg-blue-100" href="/users/{{ $article->author->id }}">{{ $article->author->name }}</a>
                    <time datetime="{{ $article->published_at }}" class="">{{ optional($article->published_at)->calendar() ?? 'nog niet gepubliceerd'}}</time>
                </em>
            </div>
            <div class="flex flex-wrap mt-2">
            @foreach ($article->taggables as $tag)
                @auth
                    <taggable-manager :tag="{{ $tag }}">
                        <template v-slot:default="{taggable}">
                            <a :href="taggable.url" class="mt-1 mr-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium opacity-50 leading-4 bg-blue-800 text-yellow-200">@{{ taggable.title }}</a>
                        </template>
                    </taggable-manager>
                @else
                    <a href="{{ $tag->url }}" class="mt-1 mr-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium opacity-50 leading-4 bg-blue-800 text-yellow-200">{{ $tag->title }}</a>
                @endauth
            @endforeach
            </div>
            <div class="mt-3 prose">
@markdown
{{ $article->body }}
@endmarkdown
            </div>
        </image-article>
    </div>
    <div class="p-4 w-full sm:w-64 flex-shrink-0">
        <aside class="">
            <article-map
                class="w-full h-64"
                :locations="{{ $article->locations }}"
            ></article-map>
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
        @include('articles.history')
        @if(auth()->check() && auth()->id() == 1)
        <aside class="mt-6">
            <form action="/articles/{{ $article->slug }}" method="POST">
                @csrf
                @method('DELETE')
                <span class="flex w-full rounded-md shadow-sm">
                    <button class="flex w-full justify-center items-center px-4 py-2 border border-red-500 text-sm leading-5 font-medium rounded-md text-red-700 bg-white hover:text-red-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-red-800 active:bg-red-50 transition ease-in-out duration-150" type="submit">Verwijderen</button>
                </span>
            </form>
        </aside>
        @endif
    </div>
</main>
<portal-target name="manage-tag-modal">
</portal-target>
<portal-target name="image-slideshow">
</portal-target>
<portal-target name="manage-location-modal">
</portal-target>
@endsection
