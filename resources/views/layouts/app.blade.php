<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <!-- Header image by Condor3d: https://commons.wikimedia.org/wiki/User:Condor3d -->
        <!-- Source: https://commons.wikimedia.org/wiki/File:Jabikspaad_wegwijzer.jpg -->
        <header class="relative bg-cover bg-center" style="background-image: url('/storage/Jabikspaad.jpg')">
            <nav class="flex justify-between bg-white bg-opacity-75 px-4 py-3">
                <div class="">
                    <a class="" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
                </div>
                <div class="">
                    @guest
                        <a class="" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @if (Route::has('register'))
                            <a class="" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    @else
                        <a class="" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </div>
            </nav>
        </header>

        @yield('content')
    </div>
</body>
</html>
