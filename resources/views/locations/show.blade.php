@extends('layouts.app')

@section('content')
<main class="flex flex-col sm:flex-row w-full">
    <div class="p-4 flex-grow">
        <h2>{{ $location->title }}</h2>
    </div>
</main>
@endsection
