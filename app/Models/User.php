<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LucasDotVin\Soulbscription\Models\Concerns\HasSubscriptions;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, LogsActivity, HasSubscriptions;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('system')
            ->logUnguarded()
            ->logOnlyDirty();
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'first_name',
            'last_name',
            'email',
            'birthday',
            'phone',
            'company_name',
            'company_code',
            'updated_at',
        ], 'like', "%$text%");
    }

    public function scopeBirthdayBetween(Builder $query, ...$interval): Builder
    {
        $table = $this->getTable();

        $query->whereDate("$table.birthday", '>=', Carbon::parseWithAppTimezone($interval[0]));

        if (!empty($interval[1])) {
            $query->whereDate("$table.birthday", '<=', Carbon::parseWithAppTimezone($interval[1]));
        }

        return $query;
    }

    public function scopeUpdatedAtBetween(Builder $query, ...$interval): Builder
    {
        $table = $this->getTable();

        $query->whereDate("$table.updated_at", '>=', Carbon::parseWithAppTimezone($interval[0]));

        if (!empty($interval[1])) {
            $query->whereDate("$table.updated_at", '<=', Carbon::parseWithAppTimezone($interval[1]));
        }

        return $query;
    }

    public function getCancelBeforeAttribute(): int
    {
        return 48;
    }

    public function getDeductedAmount(float $totalPrice): float
    {
        return min($this->balance, $totalPrice);
    }

    public function addBalance(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }

    public function deductBalance(float $amount): void
    {
        $this->balance = max(0, $this->balance - $amount);
        $this->save();
    }
}
