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
    <body class="min-h-full bg-neutral-100 text-neutral-900 antialiased dark:bg-neutral-900 dark:text-neutral-100">
        <div class="min-h-screen">
            @include('layouts.partials.navigation')

            <main class="container mx-auto px-4 py-8">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>

        @livewireScripts
        @stack('scripts')
    </body>
</html>

