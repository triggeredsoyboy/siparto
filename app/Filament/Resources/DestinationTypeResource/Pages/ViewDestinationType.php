<?php

namespace App\Filament\Resources\DestinationTypeResource\Pages;

use App\Filament\Resources\DestinationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDestinationType extends ViewRecord
{
    protected static string $resource = DestinationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
