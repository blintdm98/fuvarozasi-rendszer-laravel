@extends('layouts.auth', [
    'title' => __('Belépés'),
    'description' => __('Add meg az e-mail címed és a jelszavad a folytatáshoz.'),
])

@section('content')
    <form action="{{ route('login.login') }}" method="POST" class="space-y-6">
        @csrf

        @if ($errors->has('common'))
            <x-alert negative title="{{ $errors->first('common') }}"/>
        @endif

        <div class="space-y-1.5">
            <x-input
                label="{{ __('Email cím') }}"
                name="email"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
                value="{{ old('email') }}"
            />

            @error('email')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-1.5">
            <x-input
                label="{{ __('Jelszó') }}"
                name="password"
                type="password"
                required
                autocomplete="current-password"
                placeholder="********"
            />

            @error('password')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <x-button primary type="submit" class="w-full">
            {{ __('Belépés') }}
        </x-button>
    </form>
@endsection

