@extends('layouts.app')

@section('content')
<main class="flex flex-col sm:flex-row w-full">
    <div class="p-4 flex-grow">
        @foreach ($locations as $location)
            <div class="">
                <h2>{{ $location->title }}</h2>
                <form action="/locations/{{ $location->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="">Verwijderen</button>
                </form>
            </div>
        @endforeach
    </div>
</main>
@endsection
