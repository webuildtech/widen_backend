<?php

namespace App\Models;

use App\Models\Concerns\HasCourtScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperCourt
 */
class Court extends BaseModel implements HasMedia
{
    use InteractsWithMedia;
    use HasCourtScopes;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function courtType(): BelongsTo
    {
        return $this->belongsTo(CourtType::class);
    }

    public function intervals(): BelongsToMany
    {
        return $this->belongsToMany(Interval::class)->orderByPivot('order');
    }

    public function downtimes(): HasMany
    {
        return $this->hasMany(Downtime::class);
    }

    public function reservationSlots(): HasMany
    {
        return $this->hasMany(ReservationSlot::class);
    }

    public function logo(): Attribute
    {
        return Attribute::get(fn() => $this->getFirstMedia('logo'));
    }

    public function intervalsIds(): Attribute
    {
        return Attribute::get(fn() => $this->intervals->pluck('id')->toArray());
    }

    public function isAvailable(): Attribute
    {
        return Attribute::get(fn() => $this->active && $this->intervals()->exists());
    }
}
