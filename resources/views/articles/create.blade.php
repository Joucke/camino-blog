@extends('layouts.app')

@section('content')
<blog-form class="container mx-auto flex flex-col sm:flex-row">
    <div class="p-4 sm:pr-0 flex-grow">
        <nav class="flex items-center justify-between text-sm leading-5 font-medium">
            <div class="w-full flex flex-grow-0 items-center">
                <a href="/" class="text-blue-700 hover:text-blue-900 hover:underline transition duration-150 ease-in-out">Alle blogs</a>
                <svg class="flex-shrink-0 mx-2 h-5 w-5 text-blue-700" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-blue-900">Blog schrijven</span>
            </div>
            {{-- TODO: new image modal w/ paginated browsing, direct upload, etc
            <button class="flex w-6 h-6 items-center justify-center rounded font-bold text-lg bg-blue-800 text-yellow-200 p-1">
                <svg class="mx-auto h-4 w-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            --}}
        </nav>
        <form action="/articles" method="POST" class="mt-3 border-t border-blue-200 pt-3 sm:pt-0 sm:border-t-0">
            @csrf
            @include('articles._form')
            <div class="mt-6 border-t border-blue-200">
                <div class="flex justify-end">
                    <span class="mt-6 flex rounded-md shadow-sm">
                        <button class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-yellow-200 bg-blue-800 hover:bg-blue-900 focus:outline-none focus:border-blue-800 focus:shadow-outline-blue active:bg-blue-900 transition duration-150 ease-in-out">
                            Toevoegen
                        </button>
                    </span>
                </div>
            </div>
        </form>
    </div>
    <div class="p-4 w-full sm:w-64">
        <aside>
            <upload-form />
        </aside>
        <aside class="mt-6">
            <image-preview />
        </aside>
    </div>
</blog-form>
<portal-target name="blog-form-modal">
</portal-target>
<portal-target name="add-location-modal">
</portal-target>
<portal-target name="add-tag-modal">
</portal-target>
@endsection
