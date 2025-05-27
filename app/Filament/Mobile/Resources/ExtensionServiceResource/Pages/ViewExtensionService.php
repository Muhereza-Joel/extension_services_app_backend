<?php

namespace App\Filament\Mobile\Resources\ExtensionServiceResource\Pages;

use App\Filament\Mobile\Resources\ExtensionServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExtensionService extends ViewRecord
{
    protected static string $resource = ExtensionServiceResource::class;

    protected ?string $heading = '';
}
