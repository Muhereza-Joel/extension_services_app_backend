<?php

namespace App\Filament\Mobile\Resources\MeetingResource\Pages;

use App\Filament\Mobile\Resources\MeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMeeting extends EditRecord
{
    protected static string $resource = MeetingResource::class;

    protected ?string $heading = '';

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save Changes')
                ->submit('save') // This submits the form
                ->color('primary'),
        ];
    }
}
