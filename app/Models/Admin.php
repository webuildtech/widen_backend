<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperAdmin
 */
class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, LogsActivity, HasRoles;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function scopeGlobal(Builder $query, string $text): Builder
    {
        return $query->whereAny([
            'email',
            'first_name',
            'last_name',
            'phone',
            'updated_at'
        ], 'like', "%$text%");
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('system')
            ->logUnguarded()
            ->logOnlyDirty();
    }

    public function getRoleAttribute(): string
    {
        return $this->getRoleNames()->first();
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
}
