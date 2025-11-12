<nav class="bg-white/80 backdrop-blur shadow-sm dark:bg-neutral-800/70">
    <div class="container mx-auto flex items-center justify-between px-4 py-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-semibold text-amber-600">
            <x-icon name="truck" class="w-6 h-6" />
            <span>{{ config('app.name', 'Fuvarozási rendszer') }}</span>
        </a>

        <div class="flex items-center gap-4 text-sm">
            @auth
                <div class="hidden items-center gap-4 sm:flex">
                    <a href="{{ route('dashboard') }}" class="font-medium text-neutral-700 hover:text-amber-600 dark:text-neutral-200">
                        {{ __('Dashboard') }}
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.jobs.index') }}" class="font-medium text-neutral-700 hover:text-amber-600 dark:text-neutral-200">
                            {{ __('Admin feladatok') }}
                        </a>
                    @endif

                    @if(auth()->user()->isCarrier())
                        <a href="{{ route('carrier.jobs.index') }}" class="font-medium text-neutral-700 hover:text-amber-600 dark:text-neutral-200">
                            {{ __('Fuvarjaim') }}
                        </a>
                    @endif
                </div>

                <x-dropdown align="right">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 rounded-full border border-neutral-200 bg-white px-3 py-1 text-sm font-medium shadow-sm hover:border-amber-400 dark:border-neutral-700 dark:bg-neutral-900">
                            <x-icon name="user-circle" class="w-5 h-5" />
                            <span>{{ auth()->user()->name }}</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 text-sm">
                            <p class="font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-neutral-500">{{ auth()->user()->email }}</p>
                        </div>

                        <x-dropdown.item href="{{ route('dashboard') }}" icon="home" label="{{ __('Vezérlőpult') }}" />

                        <div class="border-t border-neutral-200 dark:border-neutral-700"></div>

                        <div class="px-2 py-1">
                            <x-dropdown.item
                                href="{{ route('logout') }}"
                                icon="arrow-left-on-rectangle"
                                label="{{ __('Kijelentkezés') }}"
                            />
                        </div>
                    </x-slot>
                </x-dropdown>
            @else
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-neutral-700 hover:text-amber-600 dark:text-neutral-200">
                        {{ __('Belépés') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

