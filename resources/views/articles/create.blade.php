@extends('layouts.app')

@section('content')
<main class="flex flex-col sm:flex-row w-full">
    <form action="/articles" method="POST" class="p-4 flex-grow">
        @csrf
        @include('articles._form')
        <button class="border">Toevoegen</button>
    </form>
    <div class="p-4 w-full sm:w-64">
        <aside>
            <form action="/photos" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" multiple name="photos[]">
                <button class="border">Uploaden</button>
        </aside>
    </div>
</main>
@endsection
