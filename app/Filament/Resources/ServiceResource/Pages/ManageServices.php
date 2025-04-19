<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums;

class ManageServices extends ManageRecords
{
    protected static string $resource = ServiceResource::class;

    protected ?string $heading = 'Layanan';

    protected ?string $subheading = 'Segala bentuk bantuan, aktivitas, atau pelayanan yang disediakan oleh manusia (pengelola, petugas, atau pihak ketiga) untuk menunjang pengalaman wisatawan.';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(Enums\MaxWidth::TwoExtraLarge),
        ];
    }
}
