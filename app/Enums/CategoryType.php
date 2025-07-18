<?php

namespace App\Enums;

enum CategoryType: string
{
    case INCOME = 'income';
    case SPENDING = 'spending';
    case TRANSFER = 'transfer';

    public function label(): string
    {
        return match ($this) {
            self::INCOME => 'Ingreso',
            self::SPENDING => 'Gasto',
            self::TRANSFER => 'Transferencia',
        };
    }

    public static function toOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
