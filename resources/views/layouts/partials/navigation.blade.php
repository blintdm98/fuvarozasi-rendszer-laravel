<nav class="bg-white/80 backdrop-blur shadow-sm dark:bg-neutral-800/70">
    <div class="container mx-auto flex items-center justify-between px-4 py-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-semibold text-amber-600">
            <x-icon name="truck" class="w-6 h-6" />
            <span>{{ config('app.name', 'Fuvarozási rendszer') }}</span>
        </a>

        <div class="flex items-center gap-4 text-sm">
            @auth
                <div class="hidden items-center gap-4 whitespace-nowrap sm:flex">
                    <a href="{{ route('dashboard') }}" class="font-medium text-neutral-700 hover:text-amber-600 dark:text-neutral-200 whitespace-nowrap">
                        {{ __('Dashboard') }}
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.jobs.index') }}" class="font-medium text-neutral-700 hover:text-amber-600 dark:text-neutral-200 whitespace-nowrap">
                            {{ __('Admin feladatok') }}
                        </a>
                    @endif

                    @if(auth()->user()->isCarrier())
                        <a href="{{ route('carrier.jobs.index') }}" class="font-medium text-neutral-700 hover:text-amber-600 dark:text-neutral-200">
                            {{ __('Fuvarjaim') }}
                        </a>
                    @endif
                </div>

                <button class="flex items-center gap-2 rounded-full border border-neutral-200 bg-white px-3 py-1 text-sm font-medium shadow-sm hover:border-amber-400 dark:border-neutral-700 dark:bg-neutral-900 whitespace-nowrap">
                    <x-icon name="user-circle" class="w-5 h-5" />
                    <span class="whitespace-nowrap">{{ auth()->user()->name }}</span>
                </button>
                <a
                    href="{{ route('logout') }}"
                    class="mt-1 flex w-full items-center gap-2 rounded-md px-4 py-2 text-sm font-semibold text-rose-600 transition-colors duration-150 hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-200"
                >
                    <x-icon name="arrow-left-on-rectangle" class="h-4 w-4" />
                    <span>{{ __('Kijelentkezés') }}</span>
                </a>
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

