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

    public const LOGO_COLLECTION = 'logo';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::LOGO_COLLECTION)->useDisk('public')->singleFile();
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
