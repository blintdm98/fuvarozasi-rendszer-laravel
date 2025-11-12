@extends('layouts.auth', [
    'title' => config('app.name'),
    'description' => __('Egyszerű fuvarmenedzsment: feladatok kiosztása, státuszok kezelése, járművek nyilvántartása.'),
])

@section('content')
    <div class="space-y-6 text-center">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-neutral-900 dark:text-white">
                {{ __('Fuvarozási rendszer') }}
            </h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Lépj be, hogy kezelhesd a fuvarfeladatokat, rendelj fuvarozókat, vagy kövesd a hozzád rendelt szállítások állapotát.') }}
            </p>
                </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
            <x-button primary icon="arrow-right" href="{{ route('login') }}">
                {{ __('Belépés') }}
            </x-button>
            <x-button flat href="https://wireui.com" target="_blank">
                {{ __('WireUI dokumentáció') }}
            </x-button>
        </div>

        <x-card class="text-left text-sm text-neutral-600 dark:text-neutral-300">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                {{ __('Fő funkciók') }}
            </h2>
            <ul class="mt-3 list-disc space-y-2 pl-4">
                <li>{{ __('Fuvarfeladatok létrehozása, szerkesztése és törlése admin felületen.') }}</li>
                <li>{{ __('Fuvarfeladatok kiosztása fuvarozókhoz és járművekhez összhangban a kapacitásokkal.') }}</li>
                <li>{{ __('Fuvarozói felület a kiosztott munkák megtekintéséhez és státuszfrissítéséhez.') }}</li>
                <li>{{ __('Átlátható státuszkezelés: kiosztva, folyamatban, elvégezve, sikertelen.') }}</li>
            </ul>
        </x-card>
    </div>
@endsection

