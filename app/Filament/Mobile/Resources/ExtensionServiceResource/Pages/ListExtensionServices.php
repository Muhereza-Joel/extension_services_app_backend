<?php

namespace App\Filament\Mobile\Resources\ExtensionServiceResource\Pages;

use App\Filament\Mobile\Resources\ExtensionServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExtensionServices extends ListRecords
{
    protected static string $resource = ExtensionServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
