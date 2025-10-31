<?php

namespace App\Models;

use App\Models\Concerns\HasBalance;
use App\Models\Concerns\HasDateScopes;
use App\Models\Concerns\HasUserScopes;
use App\Models\Concerns\LogsSystemActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LucasDotVin\Soulbscription\Models\Concerns\HasSubscriptions;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use LogsSystemActivity;
    use HasDateScopes;
    use HasSubscriptions;
    use HasUserScopes;
    use HasBalance;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function hasSubscription(): Attribute
    {
        return Attribute::get(fn() => $this->subscription()->exists());
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn() => trim("{$this->first_name} {$this->last_name}"));
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function reservations(): MorphMany
    {
        return $this->morphMany(Reservation::class, 'owner');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'owner');
    }

    public function invoices(): MorphMany
    {
        return $this->morphMany(Invoice::class, 'owner');
    }

    public function balanceEntries(): HasMany
    {
        return $this->hasMany(UserBalanceEntry::class);
    }
}
