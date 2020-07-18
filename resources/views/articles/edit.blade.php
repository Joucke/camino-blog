@extends('layouts.app')

@section('content')
<main class="flex">
    <form class="flex-grow p-4" action="/articles/{{ $article->slug }}" method="POST">
        @csrf
        @method('PATCH')
        <div>
            <div>
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Edit article: {{ $article->title }}</h3>
                </div>
                <div class="mt-6 sm:mt-5">
@include('articles._form')
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-end">
                    {{-- <span class="inline-flex rounded-md shadow-sm">
                        <button type="button" class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                            Cancel
                        </button>
                    </span> --}}
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                            Save
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </form>
    <aside class="w-full sm:w-64">images here?</aside>
</main>
@endsection
