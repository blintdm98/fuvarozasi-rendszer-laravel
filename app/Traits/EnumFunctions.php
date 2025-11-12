<?php

namespace App\Traits;

trait EnumFunctions
{
    /**
     * Return enum cases as array of ['value' => ..., 'label' => ...]
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function toArray(): array
    {
        return array_map(
            fn ($case): array => [
                'value' => $case->value,
                'label' => $case->label(),
            ],
            self::cases()
        );
    }
}

