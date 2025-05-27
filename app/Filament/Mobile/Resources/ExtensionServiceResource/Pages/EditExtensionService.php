<?php

namespace App\Filament\Mobile\Resources\ExtensionServiceResource\Pages;

use App\Filament\Mobile\Resources\ExtensionServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExtensionService extends EditRecord
{
    protected static string $resource = ExtensionServiceResource::class;

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
