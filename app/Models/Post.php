<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
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
            'body' => 'array',
            'status' => PostStatus::class,
        ];
    }

    /**
     * Defining media collection for the post.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('post-cover')
            ->useFallbackUrl('https://placehold.co/600x400');

        $this->addMediaCollection('post-media')
            ->useFallbackUrl('https://placehold.co/600x400');
    }

    /**
     * Get the author that owns the comment.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    /**
     * Get the category that owns the comment.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
