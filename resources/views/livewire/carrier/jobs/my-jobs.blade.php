<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                {{ __('Kiosztott fuvarjaim') }}
            </h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                {{ __('Frissítsd a feladatok státuszát a munka előrehaladása szerint.') }}
            </p>
        </div>

    </div>

    <div class="grid gap-4">
        @forelse ($jobs as $job)
            @php
                $selectedStatus = $statusSelections[$job->id] ?? $job->status->value;
                $hasChange = $selectedStatus !== $job->status->value;
            @endphp
            <x-card wire:key="job-{{ $job->id }}">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-2">
                        <p class="text-sm font-semibold text-amber-600">
                            {{ $job->pickup_address }} <span class="text-neutral-400">→</span> {{ $job->delivery_address }}
                        </p>
                        <p class="text-sm text-neutral-700 dark:text-neutral-200">
                            {{ $job->recipient_name }} · <a href="tel:{{ $job->recipient_phone }}" class="text-amber-600 hover:underline">{{ $job->recipient_phone }}</a>
                        </p>
                        <div class="flex flex-wrap gap-4 text-xs text-neutral-500 dark:text-neutral-400">
                            <span>{{ __('Létrehozva: :date', ['date' => $job->created_at?->format('Y.m.d H:i') ?? '—']) }}</span>
                            <span>{{ __('Aktuális státusz: :status', ['status' => $job->status->label()]) }}</span>
                            @if ($job->assigned_at)
                                <span>{{ __('Hozzárendelve: :date', ['date' => $job->assigned_at->format('Y.m.d H:i')]) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-3 w-full md:w-auto">
                        <x-badge :label="$job->status->label()" :color="$job->status->badgeColor()" />

                        <div class="flex flex-col items-end gap-2 w-full md:w-56">
                            <x-select
                                :disabled="$job->status->isTerminal()"
                                wire:model="statusSelections.{{ $job->id }}"
                                option-label="label"
                                option-value="value"
                                :options="$statusOptions"
                                class="w-full"
                            />

                            <x-button
                                primary
                                squared
                                size="sm"
                                icon="check"
                                wire:click="confirmStatus({{ $job->id }})"
                                wire:loading.attr="disabled"
                                :disabled="$job->status->isTerminal()"
                            >
                                {{ __('Frissítés') }}
                            </x-button>
                        </div>

                        @if ($job->status->isTerminal())
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 text-right">
                                {{ __('A lezárt státusz már nem módosítható.') }}
                            </p>
                        @endif
                    </div>
                </div>
            </x-card>
        @empty
            <x-card>
                <div class="py-12 text-center text-neutral-500 dark:text-neutral-400">
                    {{ __('Jelenleg nincs kiosztott feladatod.') }}
                </div>
            </x-card>
        @endforelse
    </div>
</div>
