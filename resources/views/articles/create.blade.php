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
            <aside class="mt-6">
                <image-preview />
            </aside>
        </div>
    </blog-form>
</main>
<portal-target name="blog-form-modal">
</portal-target>
<portal-target name="add-location-modal">
</portal-target>
<portal-target name="add-tag-modal">
</portal-target>
@endsection
