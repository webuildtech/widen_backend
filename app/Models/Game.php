<?php

namespace App\Models;

use App\Models\Concerns\HasGameScopes;
use App\Enums\GameStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @mixin IdeHelperGame
 */
class Game extends BaseModel implements HasMedia
{
    use InteractsWithMedia;
    use HasGameScopes;
    use HasTranslations;
    use HasUuids;

    public array $translatable = ['title', 'description'];

    protected $attributes = [
        'status' => GameStatus::PUBLISHED->value,
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'canceled_at' => 'datetime',
        'capacity' => 'integer',
        'price' => 'decimal:2',
        'vat' => 'decimal:2',
        'price_with_vat' => 'decimal:2',
        'status' => GameStatus::class,
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function courtType(): BelongsTo
    {
        return $this->belongsTo(CourtType::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class)->withTrashed();
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class)->withTrashed();
    }

    public function participants(): HasMany
    {
        return $this->hasMany(GameParticipant::class);
    }

    public function activeParticipants(): HasMany
    {
        return $this->participants()->holdingSpot();
    }

    public function reservation(): MorphOne
    {
        return $this->morphOne(Reservation::class, 'owner');
    }

    public function photo(): Attribute
    {
        return Attribute::get(fn() => $this->getFirstMedia('photo'));
    }

    public function fullName(): Attribute
    {
        return Attribute::get(fn() => $this->title
            ?: __('games.default_title', ['time' => $this->start_time?->format('Y-m-d H:i')]));
    }

    public function email(): Attribute
    {
        return Attribute::get(fn() => '');
    }

    public function phone(): Attribute
    {
        return Attribute::get(fn() => null);
    }
}
