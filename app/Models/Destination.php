<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Destination extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\DestinationFactory> */
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
     * Defining media collection for the destination.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('destination-media')
            ->useFallbackUrl('https://placehold.co/600x400');
    }

    /**
     * Get the destination type that owns the destination.
     */
    public function destinationType(): BelongsTo
    {
        return $this->belongsTo(DestinationType::class);
    }

    /**
     * The facilities that belong to the destination.
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class)->withTimestamps();
    }

    /**
     * Get all of the packages for the itinerary.
     */
    public function packages(): MorphToMany
    {
        return $this->morphToMany(Package::class, 'packable')->withPivot('id')->withTimestamps();
    }
}
