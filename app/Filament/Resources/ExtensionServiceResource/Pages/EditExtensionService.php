<?php

namespace App\Filament\Resources\ExtensionServiceResource\Pages;

use App\Filament\Resources\ExtensionServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExtensionService extends EditRecord
{
    protected static string $resource = ExtensionServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
