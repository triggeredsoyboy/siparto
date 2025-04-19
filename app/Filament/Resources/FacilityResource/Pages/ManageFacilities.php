<?php

namespace App\Filament\Resources\FacilityResource\Pages;

use App\Filament\Resources\FacilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums;

class ManageFacilities extends ManageRecords
{
    protected static string $resource = FacilityResource::class;

    protected ?string $heading = 'Fasilitas';

    protected ?string $subheading = 'Sarana fisik atau infrastruktur pada destinasi wisata yang dapat digunakan langsung oleh pengunjung.';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(Enums\MaxWidth::TwoExtraLarge),
        ];
    }
}
