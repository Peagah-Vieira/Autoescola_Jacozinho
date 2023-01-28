<?php

namespace App\Filament\Resources\PaymentResource\Widgets;

use App\Models\Payment;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class PaymentStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Payments', Payment::all()->count()),
            Card::make('Payments Amount', Payment::all()->sum('amount')),
            Card::make('Payments Amount 7 days', Payment::all()->where('created_at', '>=', Carbon::now()->subDays(7))->sum('amount')),
        ];
    }
}
