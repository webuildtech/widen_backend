<?php

namespace App\Models;

use App\Enums\GameParticipantStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperGameParticipant
 */
class GameParticipant extends BaseModel
{
    protected $attributes = [
        'status' => GameParticipantStatus::PENDING->value,
    ];

    protected $casts = [
        'status' => GameParticipantStatus::class,
        'price' => 'decimal:2',
        'vat' => 'decimal:2',
        'discount' => 'decimal:2',
        'price_with_vat' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'joined_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by_user_id')->withTrashed();
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(GameLevel::class, 'game_level_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn() => trim("{$this->first_name} {$this->last_name}") ?: $this->email);
    }

    public function scopeHoldingSpot(Builder $query): Builder
    {
        return $query->whereIn('status', [GameParticipantStatus::PENDING, GameParticipantStatus::CONFIRMED]);
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', GameParticipantStatus::CONFIRMED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', GameParticipantStatus::PENDING);
    }
}
