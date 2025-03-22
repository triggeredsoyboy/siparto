<?php

namespace App\Filament\Resources\FacilityResource\Pages;

use App\Filament\Resources\FacilityResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support;

class ManageFacilities extends ManageRecords
{
    protected static string $resource = FacilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(Support\Enums\MaxWidth::TwoExtraLarge)
                ->modalHeading('Buat Fasilitas Baru')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Berhasil')
                        ->body('Fasilitas baru berhasil ditambahkan.'),
                ),
        ];
    }
}
