<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Culture extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CultureFactory> */
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'schedules' => 'array',
        ];
    }

    /**
     * Defining media collection for the culture.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('culture-media')
            ->useFallbackUrl('https://placehold.co/600x400');
    }
}
