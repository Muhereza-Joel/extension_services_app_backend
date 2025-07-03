<?php

namespace App\Filament\Widgets;

use App\Models\Meeting;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;


class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $user->hasRole('root')) {
            return [
                Stat::make('Total Meetings', Meeting::count())
                    ->description('All meetings in system')
                    ->descriptionIcon('heroicon-o-calendar')
                    ->chart($this->getMeetingsChartData())
                    ->color('primary'),

                Stat::make('Upcoming Meetings', Meeting::where('status', 'upcoming')->count())
                    ->description('Meetings not yet started')
                    ->descriptionIcon('heroicon-o-clock')
                    ->color('info'),

                Stat::make('Active Bookings', Ticket::where('status', 'confirmed')->count())
                    ->description('Confirmed tickets')
                    ->descriptionIcon('heroicon-o-ticket')
                    ->chart($this->getBookingsChartData())
                    ->color('success'),

                Stat::make('Total Revenue', 'UGX ' . number_format(Ticket::sum('price')))
                    ->description('From all bookings')
                    ->descriptionIcon('heroicon-o-currency-dollar')
                    ->color('warning'),

                Stat::make('Avg. Attendance', Meeting::withCount('tickets')->get()->avg('tickets_count'))
                    ->description('Tickets per meeting')
                    ->descriptionIcon('heroicon-o-users')
                    ->color('danger'),
            ];
        }

        if ($user->hasRole('farmer')) {
            return [];
        }

        if ($user->hasRole('extension officer')) {
            return [];
        }

        // Default fallback if role is missing or unknown
        return [
            Stat::make('Meetings', Meeting::count())
                ->description('Meetings in system')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('gray'),
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
