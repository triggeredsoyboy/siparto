<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum PostStatus: string implements HasLabel, HasColor, HasDescription
{
    case Draft = 'draft';
    case Published = 'published';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Draf',
            self::Published => 'Diterbitkan',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'success',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::Draft => 'Belum selesai ditulis.',
            self::Published => 'Dipublikasikan di situs web',
        };
    }
}
