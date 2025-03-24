<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Itinerary extends Model
{
    /** @use HasFactory<\Database\Factories\ItineraryFactory> */
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activities' => 'array',
        ];
    }

    /**
     * Get all of the packages for the itinerary.
     */
    public function packages(): MorphToMany
    {
        return $this->morphToMany(Package::class, 'packable')->withPivot('id')->withTimestamps();
    }
}
