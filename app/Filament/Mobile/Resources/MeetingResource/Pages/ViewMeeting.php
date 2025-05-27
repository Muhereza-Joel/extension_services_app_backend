<?php

namespace App\Filament\Mobile\Resources\MeetingResource\Pages;

use App\Filament\Mobile\Resources\MeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMeeting extends ViewRecord
{
    protected static string $resource = MeetingResource::class;

    protected ?string $heading = '';
}
