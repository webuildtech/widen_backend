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

    public function scopeUpdatedAtBetween(Builder $query, string $start, ?string $end = null): Builder
    {
        return $query->dateBetween('updated_at', $start, $end);
    }

    public function scopeDateBetween(Builder $query, string $column, string $start, ?string $end = null): Builder
    {
        $query->whereDate("{$this->getTable()}.$column", '>=', Carbon::parseWithAppTimezone($start));

        if ($end !== null) {
            $query->whereDate("{$this->getTable()}.$column", '<=', Carbon::parseWithAppTimezone($end));
        }

        return $query;
    }
}
