<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Enums\CategoryType;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class IncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Reporte de ingresos del año ';

    public function getHeading(): string
    {
        return static::$heading . now()->year;
    }

    protected function getData(): array
    {
        $data = Transaction::where('categories.type', CategoryType::INCOME->value)
            // ->selectRaw('SUM(amount) as total, MONTH(date) as month')
            ->selectRaw('SUM(amount) as total, strftime("%m",date) as month')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->groupBy('month')
            ->orderBy('month')
            ->whereYear('date', now()->year)
            ->get();
        $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $totalRevenue = array_fill(0, 12, 0);
        foreach ($data as $item) {
            $totalRevenue[$item->month - 1] = $item->total;
        }
        return [
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => $totalRevenue,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
