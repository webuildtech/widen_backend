<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @mixin IdeHelperGameGroup
 */
class GameGroup extends BaseModel implements HasMedia
{
    use HasTranslations;
    use HasUuids;
    use InteractsWithMedia;

    public array $translatable = ['name', 'description'];

    protected $attributes = [
        'sort_order' => 0,
        'active' => true,
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'active' => 'boolean',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public const PHOTO_COLLECTION = 'photo';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::PHOTO_COLLECTION)->singleFile();
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function photo(): Attribute
    {
        return Attribute::get(fn() => $this->getFirstMedia('photo'));
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
