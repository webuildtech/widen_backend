<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @mixin IdeHelperBaseModel
 */
class BaseModel extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('system')
            ->logUnguarded()
            ->logOnlyDirty();
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
