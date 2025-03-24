<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Package extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
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
     * Defining media collection for the packages.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('package-media')
            ->useFallbackUrl('https://placehold.co/600x400');
    }

    /**
     * Get all of the destinations that are assigned this package.
     */
    public function destinations(): MorphToMany
    {
        return $this->morphedByMany(Destination::class, 'packable')->withPivot('id')->withTimestamps();
    }

    /**
     * Get all of the itineraries that are assigned this package.
     */
    public function itineraries(): MorphToMany
    {
        return $this->morphedByMany(Itinerary::class, 'packable')->withPivot('id')->withTimestamps();
    }
}
