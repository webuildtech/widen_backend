<?php

namespace App\Models;

use App\Enums\Day;
use App\Enums\DiscountCodeType;
use App\Models\Concerns\HasDateRangeScopes;
use App\Data\Core\DiscountCodes\DiscountLineData;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function windows(): HasMany
    {
        return $this->hasMany(DiscountCodeWindow::class);
    }

    public function courtTypesIds(): Attribute
    {
        return Attribute::get(fn() => $this->courtTypes->pluck('id')->toArray());
    }

    public function isLimitedToCourtTypes(): bool
    {
        return count($this->court_types_ids) > 0;
    }

    public function isLimitedToWindows(): bool
    {
        return $this->windows->isNotEmpty();
    }

    public function appliesToCourtType(?int $courtTypeId): bool
    {
        return !$this->isLimitedToCourtTypes() || in_array($courtTypeId, $this->court_types_ids);
    }

    public function appliesToTime(?CarbonInterface $startsAt): bool
    {
        if (!$this->isLimitedToWindows()) {
            return true;
        }

        if (!$startsAt) {
            return false;
        }

        $day = Day::from(strtolower($startsAt->format('D')));
        $time = $startsAt->format('H:i');

        return $this->windows->contains(fn(DiscountCodeWindow $window) => $window->day === $day && $window->covers($time));
    }

    public function appliesTo(DiscountLineData $line): bool
    {
        return $this->appliesToCourtType($line->court_type_id) && $this->appliesToTime($line->starts_at);
    }
}
