<?php

namespace App\Filament\Resources\AmenityResource\Pages;

use App\Filament\Resources\AmenityResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums;

class ManageAmenities extends ManageRecords
{
    protected static string $resource = AmenityResource::class;

    protected ?string $heading = 'Amenitas';

    protected ?string $subheading = 'Hal-hal penunjang kenyamanan yang biasanya lebih kecil tetapi menambah kualitas kunjungan.';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(Enums\MaxWidth::TwoExtraLarge),
        ];
    }
}
