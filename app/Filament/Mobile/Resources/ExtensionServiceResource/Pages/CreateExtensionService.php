<?php

namespace App\Filament\Mobile\Resources\ExtensionServiceResource\Pages;

use App\Filament\Mobile\Resources\ExtensionServiceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateExtensionService extends CreateRecord
{
    protected static string $resource = ExtensionServiceResource::class;

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
