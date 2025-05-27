<?php

namespace App\Filament\Widgets;

use App\Models\Meeting;
use Filament\Widgets\ChartWidget;

class MeetingStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Meeting Status Distribution';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $statusCounts = Meeting::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'datasets' => [
                [
                    'label' => 'Meetings by Status',
                    'data' => array_values($statusCounts->toArray()),
                    'backgroundColor' => [
                        '#3b82f6', // upcoming - blue
                        '#10b981', // ongoing - green
                        '#6b7280', // completed - gray
                        '#ef4444', // cancelled - red
                    ],
                ],
            ],
            'labels' => array_keys($statusCounts->toArray()),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
