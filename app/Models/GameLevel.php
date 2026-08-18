<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

/**
 * @mixin IdeHelperGameLevel
 */
class GameLevel extends BaseModel
{
    use HasTranslations;

    public array $translatable = ['name', 'description'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function courtTypes(): BelongsToMany
    {
        return $this->belongsToMany(CourtType::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
