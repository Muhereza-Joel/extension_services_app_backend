<?php

namespace App\Filament\Mobile\Resources\MeetingResource\Pages;

use App\Filament\Mobile\Resources\MeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMeeting extends CreateRecord
{
    protected static string $resource = MeetingResource::class;

    protected ?string $heading = '';

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save')
                ->submit('save') // This submits the form
                ->color('primary'),
        ];
    }
}
