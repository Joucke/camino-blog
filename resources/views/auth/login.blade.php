@extends('layouts.app')

@section('content')
<main class="container mx-auto">
    <div class="w-full flex flex-col justify-center sm:px-6 lg:px-8 mb-6">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h1 class="mt-6 text-center text-2xl font-bold">
                {{ __('Login') }}
            </h1>
        </div>

        <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="px-4 sm:px-10">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="">
                        <label for="email" class="block text-sm font-medium leading-5 text-blue-800">{{ __('E-Mail Address') }}</label>

                        <div class="mt-1 rounded-md shadow-sm">
                            <input id="email" type="email" class="appearance-none block w-full px-3 py-2 border border-blue-500 rounded-md placeholder-blue-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="password" class="block text-sm font-medium leading-5 text-blue-800">{{ __('Password') }}</label>

                        <div class="mt-1 rounded-md shadow-sm">
                            <input id="password" type="password" class="appearance-none block w-full px-3 py-2 border border-blue-500 rounded-md placeholder-blue-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <div class="flex items-center">
                            <input class="form-checkbox h-4 w-4 border-blue-500 focus:border-blue-500 text-blue-600 transition duration-150 ease-in-out" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="ml-2 block text-sm leading-5 text-blue-800" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <div class="text-sm leading-5">
                        @if (Route::has('password.request'))
                            <a class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <span class="block w-full rounded-md shadow-sm">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out">
                                {{ __('Login') }}
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
