<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperAdvertisement
 */
class Advertisement extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->useDisk('public')->singleFile();
    }

    public function logo(): Attribute
    {
        return Attribute::get(fn() => $this->getFirstMedia('logo'));
    }

    public function logoUrl(): Attribute
    {
        return Attribute::get(fn() => $this->getFirstMedia('logo')?->getUrl());
    }
}
