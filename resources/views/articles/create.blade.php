@extends('layouts.app')

@section('content')
<form action="/articles" method="POST">
    @csrf
    @include('articles._form')
    <button class="border">Save</button>
</form>
@endsection
