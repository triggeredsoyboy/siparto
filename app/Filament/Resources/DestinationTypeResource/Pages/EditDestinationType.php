<?php

namespace App\Filament\Resources\DestinationTypeResource\Pages;

use App\Filament\Resources\DestinationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\DestinationType;

class EditDestinationType extends EditRecord
{
    protected static string $resource = DestinationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->modalHeading(fn(DestinationType $record): string => 'Hapus ' . $record->name)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Berhasil')
                        ->body('Tipe destinasi berhasil dihapus.'),
                ),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Berhasil')
            ->body('Tipe destinasi berhasil diperbarui.');
    }
}
