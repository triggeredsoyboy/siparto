<?php

namespace App\Filament\Resources\DestinationTypeResource\Pages;

use App\Filament\Resources\DestinationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDestinationTypes extends ListRecords
{
    protected static string $resource = DestinationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
