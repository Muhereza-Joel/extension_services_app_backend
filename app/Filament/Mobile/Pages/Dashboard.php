<?php

namespace App\Filament\Mobile\Pages;

use App\Filament\Mobile\Widgets\StatsOverview; // Your widget class
use App\Filament\Widgets\BookingsChart;
use App\Filament\Widgets\MeetingStatusChart;
use App\Filament\Widgets\StatsOverview as WidgetsStatsOverview;
use Filament\Pages\Page;

class Dashboard extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.mobile.pages.dashboard';
    protected ?string $heading = '';

    // Define which widgets to show
    protected function getHeaderWidgets(): array
    {
        return [
            WidgetsStatsOverview::class,
            MeetingStatusChart::class,
            BookingsChart::class
        ];
    }
}
