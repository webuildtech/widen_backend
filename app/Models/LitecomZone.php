<?php

namespace App\Models;

use App\Models\Concerns\HasDateScopes;
use App\Models\Concerns\LogsSystemActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperLitecomZone
 */
class LitecomZone extends Model
{
    use HasDateScopes;
    use LogsSystemActivity;

    protected $guarded = [];

    protected $casts = [
        'manual_override_until' => 'datetime',
        'force_auto_scene' => 'boolean'
    ];

    public function courts(): BelongsToMany
    {
        return $this->belongsToMany(Court::class);
    }

    public function courtsIds(): Attribute
    {
        return Attribute::get(fn() => $this->courts->pluck('id')->toArray());
    }
}
