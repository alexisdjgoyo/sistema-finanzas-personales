<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DashboardWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Saldo disponible',"$" . Account::find(1)->current_balance)
                ->description('Saldo disponible en cuenta principal')
                ->icon('heroicon-o-currency-dollar')
                ->color('success')
                ->chart([1,0,1,0,1,0,1]),
            // Stat::make('Total de Cuentas',Account::count())
            //     ->description('Total de cuentas registradas')
            //     ->icon('heroicon-o-banknotes')
            //     ->color('success')
            //     ->chart([1,2,3,2,5,7]),
            Stat::make('Total de Transacciones',Transaction::count())
                ->description('Total de transacciones registradas')
                ->icon('heroicon-o-rectangle-stack')
                ->color('warning')
                ->chart([1,2,3,2,5,7]),
            Stat::make('Total de categorías',Category::count())
                ->description('Total de categorías registradas')
                ->icon('heroicon-o-briefcase')
                ->color('primary')
                ->chart([1,8,2,4,1,7]),
        ];
    }
}
