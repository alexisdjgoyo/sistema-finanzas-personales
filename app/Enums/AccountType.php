<?php

namespace App\Enums;

enum AccountType: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';
    case CASH = 'cash';
    case INVESTMENT = 'investment';
    case OTHER = 'other';
    case SAVINGS = 'savings';
    case LOAN = 'loan';

    public function label(): string
    {
        return match ($this) {
            self::DEBIT => 'Débito',
            self::CREDIT => 'Crédito',
            self::CASH => 'Efectivo',
            self::INVESTMENT => 'Inversión',
            self::OTHER => 'Otro',
            self::SAVINGS => 'Cuenta de ahorro',
            self::LOAN => 'Préstamo',
        };
    }

    public static function toOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
