<?php

namespace App\Models;

use App\Enums\DiscountCodeType;
use App\Models\Concerns\HasDateRangeScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperDiscountCode
 */
class DiscountCode extends BaseModel
{
    use HasDateRangeScopes;

    protected $casts = [
        'value' => 'decimal:2',
        'type' => DiscountCodeType::class,
    ];

    public function courtTypes(): BelongsToMany
    {
        return $this->belongsToMany(CourtType::class);
    }

    public function courtTypesIds(): Attribute
    {
        return Attribute::get(fn() => $this->courtTypes->pluck('id')->toArray());
    }

    public function isLimitedToCourtTypes(): bool
    {
        return count($this->court_types_ids) > 0;
    }

    public function appliesToCourtType(?int $courtTypeId): bool
    {
        return !$this->isLimitedToCourtTypes() || in_array($courtTypeId, $this->court_types_ids);
    }

    public function appliesToAnyCourtType(array $courtTypeIds): bool
    {
        return !$this->isLimitedToCourtTypes() || count(array_intersect($this->court_types_ids, $courtTypeIds)) > 0;
    }
}
