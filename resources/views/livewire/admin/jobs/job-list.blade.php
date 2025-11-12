<div class="space-y-6">
    <x-modal-card
        blur="md"
        icon="exclamation-triangle"
        title="{{ __('Sikertelen fuvarok') }}"
        wire:model="showAlertModal"
    >
        @if (! empty($alerts))
            <div class="space-y-4">
                @foreach ($alerts as $alert)
                    <div class="rounded-lg border border-rose-200 bg-rose-50/70 p-4 dark:border-rose-900/60 dark:bg-rose-950/40">
                        <p class="font-semibold text-rose-700 dark:text-rose-300">
                            {{ $alert['message'] }}
                        </p>
                        @if (! empty($alert['job']))
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                                {{ $alert['job']['pickup'] ?? '—' }} →
                                {{ $alert['job']['delivery'] ?? '—' }}
                                <span class="ml-2 text-xs text-neutral-500 dark:text-neutral-400">
                                    ({{ $alert['job']['status'] ?? '' }})
                                </span>
                            </p>
                        @endif
                        @if (! empty($alert['created_at']))
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                {{ __('Érkezett: :date', ['date' => $alert['created_at']]) }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Jelenleg nincs figyelmeztetés.') }}
            </p>
        @endif

        <x-slot name="footer">
            <div class="flex justify-end">
                <x-button primary icon="check" wire:click="acknowledgeAlerts">
                    {{ __('Rendben') }}
                </x-button>
            </div>
        </x-slot>
    </x-modal-card>

    <div class="flex flex-col gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                {{ __('Fuvarfeladatok') }}
            </h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                {{ __('Itt kezelheted az összes feladatot és hozzárendelheted a fuvarozókhoz.') }}
            </p>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <x-select
                label="{{ __('Státusz szűrő') }}"
                placeholder="{{ __('Összes státusz') }}"
                wire:model.live.debounce.500ms="statusFilter"
                option-label="label"
                option-value="value"
                :options="$statusOptions"
                clearable
                class="sm:w-64"
            />

            <x-button primary icon="plus" wire:click="openNewJob">
                {{ __('Új feladat') }}
            </x-button>
        </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700 text-sm">
                <thead>
                    <tr class="text-left text-neutral-500 uppercase tracking-wide dark:text-neutral-400">
                        <th class="px-4 py-3">{{ __('Feladat') }}</th>
                        <th class="px-4 py-3">{{ __('Címzett') }}</th>
                        <th class="px-4 py-3">{{ __('Státusz') }}</th>
                        <th class="px-4 py-3">{{ __('Fuvarozó') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Műveletek') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                    @forelse ($jobs as $job)
                        <tr class="hover:bg-amber-50/40 dark:hover:bg-neutral-800/40">
                            <td class="px-4 py-3">
                                <div class="font-medium text-neutral-900 dark:text-neutral-100">
                                    {{ $job->pickup_address }}
                                </div>
                                <div class="text-xs text-neutral-500">
                                    {{ __('→ :address', ['address' => $job->delivery_address]) }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $job->recipient_name }}</div>
                                <div class="text-xs text-neutral-500">{{ $job->recipient_phone }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <x-badge :label="$job->status->label()" :color="$job->status->badgeColor()" />
                            </td>
                            <td class="px-4 py-3">
                                @if ($job->carrier)
                                    <div class="font-medium">{{ $job->carrier->name }}</div>
                                    <div class="text-xs text-neutral-500">{{ $job->carrier->vehicle?->license_plate }}</div>
                                @else
                                    <span class="text-xs text-neutral-500">{{ __('Nincs hozzárendelve') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <x-button flat sm icon="user-plus" wire:click="openAssignJob({{ $job->id }})">
                                        {{ __('Hozzárendelés') }}
                                    </x-button>
                                    <x-button sm wire:click="openEditJob({{ $job->id }})">
                                        {{ __('Szerkesztés') }}
                                    </x-button>
                                    <x-button negative sm icon="trash" wire:click="confirmDelete({{ $job->id }})">
                                        {{ __('Törlés') }}
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-neutral-500 dark:text-neutral-400">
                                {{ __('Jelenleg nincs feladat a rendszerben.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <x-modal-card
        blur="md"
        width="3xl"
        wire:model="jobModal"
        :title="$form->isEditing() ? __('Fuvarfeladat szerkesztése') : __('Új fuvarfeladat')"
    >
        <form wire:submit.prevent="saveJob" class="grid gap-5 md:grid-cols-2">
            <x-input
                label="{{ __('Kiindulási cím') }}"
                wire:model.defer="form.pickup_address"
                required
                class="md:col-span-2"
            />

            <x-input
                label="{{ __('Érkezési cím') }}"
                wire:model.defer="form.delivery_address"
                required
                class="md:col-span-2"
            />

            <x-input
                label="{{ __('Címzett neve') }}"
                wire:model.defer="form.recipient_name"
                required
            />

            <x-input
                label="{{ __('Címzett telefonszáma') }}"
                wire:model.defer="form.recipient_phone"
                required
            />

            <x-select
                label="{{ __('Feladat státusza') }}"
                wire:model.defer="form.status"
                option-label="label"
                option-value="value"
                :options="$statusOptions"
                class="md:col-span-2"
            />

            <x-select
                label="{{ __('Fuvarozó (opcionális)') }}"
                wire:model.defer="form.carrier_id"
                placeholder="{{ __('Nincs hozzárendelve') }}"
                option-label="label"
                option-value="value"
                :options="$carriers"
                class="md:col-span-2"
            />

            <div class="md:col-span-2 flex items-center justify-end gap-2">
                <x-button flat wire:click="$set('jobModal', false)">
                    {{ __('Mégsem') }}
                </x-button>
                <x-button primary type="submit">
                    {{ $form->isEditing() ? __('Változtatások mentése') : __('Feladat létrehozása') }}
                </x-button>
            </div>
        </form>
    </x-modal-card>

    <x-modal-card
        blur="md"
        width="2xl"
        wire:model="assignModal"
        title="{{ __('Feladat hozzárendelése') }}"
    >
        @if ($assignJobPreview)
            <x-card class="mb-5 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">{{ __('Kiindulási cím') }}</span>
                    <span class="text-neutral-900 dark:text-neutral-100">{{ $assignJobPreview['pickup'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">{{ __('Érkezési cím') }}</span>
                    <span class="text-neutral-900 dark:text-neutral-100">{{ $assignJobPreview['delivery'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">{{ __('Címzett') }}</span>
                    <span class="text-neutral-900 dark:text-neutral-100">{{ $assignJobPreview['recipient'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">{{ __('Aktuális fuvarozó') }}</span>
                    <span class="text-neutral-900 dark:text-neutral-100">
                        {{ $assignJobPreview['carrier'] ?? __('Nincs hozzárendelve') }}
                    </span>
                </div>
            </x-card>
        @endif

        <form wire:submit.prevent="assignJob" class="space-y-5">
            <x-select
                label="{{ __('Fuvarozó kiválasztása') }}"
                wire:model.defer="assignCarrierId"
                placeholder="{{ __('Válassz fuvarozót') }}"
                option-label="label"
                option-value="value"
                :options="$carriers"
            />

            <div class="flex items-center justify-end gap-2">
                <x-button flat wire:click="$set('assignModal', false)">
                    {{ __('Mégsem') }}
                </x-button>
                <x-button primary type="submit" icon="check">
                    {{ __('Hozzárendelés mentése') }}
                </x-button>
            </div>
        </form>
    </x-modal-card>

    <x-modal-card
        blur="md"
        wire:model="deleteModal"
        title="{{ __('Feladat törlése') }}"
    >
        <div class="space-y-4">
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Biztosan törlöd ezt a fuvarfeladatot? A művelet nem visszavonható.') }}
            </p>

            @if ($deleteJobLabel)
                <x-alert icon="information-circle" title="{{ $deleteJobLabel }}" class="text-sm flex flex-row gap-3"></x-alert>
            @endif

            <div class="flex items-center justify-end gap-2">
                <x-button flat wire:click="$set('deleteModal', false)">
                    {{ __('Mégsem') }}
                </x-button>
                <x-button negative icon="trash" wire:click="deleteJob">
                    {{ __('Feladat törlése') }}
                </x-button>
            </div>
        </div>
    </x-modal-card>
</div>

