<?php

namespace App\Filament\Widgets;

use App\Models\Meeting;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Total Meetings Stat
            Stat::make('Total Meetings', Meeting::count())
                ->description('All meetings in system')
                ->descriptionIcon('heroicon-o-calendar')
                ->chart($this->getMeetingsChartData())
                ->color('primary'),

            // Upcoming Meetings Stat
            Stat::make('Upcoming Meetings', Meeting::where('status', 'upcoming')->count())
                ->description('Meetings not yet started')
                ->descriptionIcon('heroicon-o-clock')
                ->color('info'),

            // Active Bookings Stat
            Stat::make('Active Bookings', Ticket::where('status', 'confirmed')->count())
                ->description('Confirmed tickets')
                ->descriptionIcon('heroicon-o-ticket')
                ->chart($this->getBookingsChartData())
                ->color('success'),

            // Revenue Stat
            Stat::make('Total Revenue', 'UGX ' . number_format(Ticket::sum('price')))
                ->description('From all bookings')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('warning'),

            // Average Attendance Stat
            Stat::make('Avg. Attendance', Meeting::withCount('tickets')->get()->avg('tickets_count'))
                ->description('Tickets per meeting')
                ->descriptionIcon('heroicon-o-users')
                ->color('danger'),
        ];
    }

    protected function getMeetingsChartData(): array
    {
        return Meeting::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date', 'created_at') // Add created_at to GROUP BY
            ->orderBy('date')
            ->pluck('count')
            ->toArray();
    }

    protected function getBookingsChartData(): array
    {
        return Ticket::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date', 'created_at') // Add created_at to GROUP BY
            ->orderBy('date')
            ->pluck('count')
            ->toArray();
    }
}
