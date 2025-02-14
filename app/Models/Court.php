<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin IdeHelperCourt
 */
class Court extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'name',
            'inside_name',
        ], 'like', "%$text%");
    }

    public function getLogoAttribute(): ?Media
    {
        return $this->getFirstMedia('logo');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }
}
