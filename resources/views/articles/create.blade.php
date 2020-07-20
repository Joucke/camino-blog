@extends('layouts.app')

@section('content')
<main>
    <blog-form class="flex flex-col sm:flex-row w-full">
        <form action="/articles" method="POST" class="p-4 flex-grow">
            @csrf
            @include('articles._form')
            <button class="border">Toevoegen</button>
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
@endsection
