<?php

namespace App\Filament\Resources\ExtensionServiceResource\Pages;

use App\Filament\Resources\ExtensionServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExtensionService extends ViewRecord
{
    protected static string $resource = ExtensionServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
