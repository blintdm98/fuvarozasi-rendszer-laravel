<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ ($title ?? null) ? "{$title} | " . config('app.name') : config('app.name') }}</title>

        <wireui:scripts />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
        @livewireStyles
    </head>
    <body class="min-h-full bg-neutral-100 antialiased dark:bg-neutral-900">
        <div class="flex min-h-screen items-center justify-center px-4 py-10">
            <div class="w-full max-w-md space-y-6">
                <div class="flex flex-col items-center gap-2 text-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-amber-600">
                        <x-icon name="truck" class="w-8 h-8" />
                        <span class="text-xl font-semibold">{{ config('app.name', 'Fuvarozási rendszer') }}</span>
                    </a>

                    @isset($title)
                        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ $title }}</h1>
                    @endisset

                    @isset($description)
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $description }}</p>
                    @endisset
                </div>

                <x-card class="space-y-6">
                    @yield('content')
                </x-card>
            </div>
        </div>

        @livewireScripts
        @stack('scripts')
    </body>
</html>

