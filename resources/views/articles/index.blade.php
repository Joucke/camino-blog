@extends('layouts.app')

@section('content')
<main class="container mx-auto flex flex-col sm:flex-row">
    <div class="p-4 sm:pr-0 flex-grow">
        <nav class="flex items-center justify-between text-sm leading-5 font-medium">
            <div class="w-full flex flex-grow-0 items-center">
                @if($parent)
                <a href="/" class="text-blue-700 hover:text-blue-900 hover:underline transition duration-150 ease-in-out">Alle blogs</a>
                <svg class="flex-shrink-0 mx-2 h-5 w-5 text-blue-700" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-blue-900">{{ $parent->title ?? $parent->name }}</span>
                @else
                <span class="text-blue-900">Alle blogs</span>
                @endif
            </div>
            @auth
            <a href="/articles/create" title="Blog schrijven" class="flex w-6 h-6 items-center justify-center rounded font-bold text-lg bg-blue-800 text-yellow-200 py-2 px-2">+</a>
            @endauth
        </nav>
        @forelse($articles as $article)
        <article class="mt-3 border-t pt-3">
            <div class="flex flex-col lg:flex-row lg:items-baseline lg:justify-between">
                <h3 class="text-2xl tracking-wide font-bold">
                    <a class="" href="/articles/{{ $article->slug }}">{{ $article->title }}</a>
                </h3>
                <em class="lg:ml-3 text-xs">
                    <time datetime="{{ $article->published_at }}" class="">{{ optional($article->published_at)->calendar() ?? 'nog niet gepubliceerd'}}</time>
                    <span> door <a class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium leading-4 bg-blue-100" href="/users/{{ $article->author->id }}">{{ $article->author->name }}</a></span>
                </em>
            </div>
            <div class="flex flex-wrap mt-2">
            @foreach ($article->taggables as $tag)
                <taggable-manager :tag="{{ $tag }}">
                    <template v-slot:default="{taggable}">
                        <a :href="taggable.url" class="mt-1 mr-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium opacity-50 leading-4 bg-blue-800 text-yellow-200">@{{ taggable.title }}</a>
                    </template>
                </taggable-manager>
            @endforeach
            </div>
            <div class="mt-3 prose">
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
        @if ($articles->hasPages())
        <nav>
            {{ $articles->onEachSide(1)->links() }}
        </nav>
        @endif
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
            <div>
                <a href="" class="mt-1 mr-1 inline-flex items-center text-xs font-medium leading-4 capitalize">{{ $month->published_month->isoFormat('MMMM') }} ({{ $month->articles_count }})</a>
            </div>
            @endforeach
            @endforeach
        </aside>
    </div>
</main>
<portal-target name="manage-tag-modal">
</portal-target>
<portal-target name="manage-location-modal">
</portal-target>
@endsection
