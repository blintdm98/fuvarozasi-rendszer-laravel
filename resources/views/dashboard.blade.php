@extends('layouts.app', ['title' => __('Dashboard')])

@section('content')
    <div class="grid gap-6 md:grid-cols-3">
        <x-card class="space-y-1">
            <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Aktív feladatok') }}</p>
            <p class="text-3xl font-semibold text-neutral-900 dark:text-white">{{ $activeJobs ?? 0 }}</p>
        </x-card>
        <x-card class="space-y-1">
            <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Teljesített fuvarok') }}</p>
            <p class="text-3xl font-semibold text-neutral-900 dark:text-white">{{ $completedJobs ?? 0 }}</p>
        </x-card>
        <x-card class="space-y-1">
            <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Fuvarozók száma') }}</p>
            <p class="text-3xl font-semibold text-neutral-900 dark:text-white">{{ $carrierCount ?? 0 }}</p>
        </x-card>
    </div>

    <x-card class="mt-6">
        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Üdv a fuvarozási rendszerben!') }}</h2>
        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
            {{ __('A fenti mutatók hamarosan valós adatokkal készülnek. Addig is, készíts új feladatokat és oszd ki a fuvarozóidnak!') }}
        </p>
    </x-card>
@endsection
