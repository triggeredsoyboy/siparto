<?php

namespace App\Filament\Resources\DestinationTypeResource\Pages;

use App\Filament\Resources\DestinationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateDestinationType extends CreateRecord
{
    protected static string $resource = DestinationTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Berhasil')
            ->body('Tipe destinasi baru berhasil ditambahkan.');
    }
}
