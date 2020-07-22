@extends('layouts.app')

@section('content')
<main>
    <blog-form class="flex flex-col sm:flex-row w-full">
        <form class="flex-grow p-4" action="/articles/{{ $article->slug }}" method="POST">
            @csrf
            @method('PATCH')
            <div>
                <div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Blog bewerken: {{ $article->title }}</h3>
                    </div>
                    <div class="mt-6 sm:mt-5">
    @include('articles._form')
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-5">
                    <div class="flex justify-end">
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                                Opslaan
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
        <div class="p-4 w-full sm:w-64">
            <aside>
                <upload-form />
            </aside>
            <aside>
                <image-preview />
            </aside>
        </div>
    </blog-form>
</main>
<portal-target name="blog-form-modal">
</portal-target>
<portal-target name="add-location-modal">
</portal-target>
@endsection
