<?php

namespace App\Models;

use App\Models\Concerns\HasDateScopes;
use App\Models\Concerns\LogsSystemActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperBaseModel
 */
class BaseModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasDateScopes;
    use LogsSystemActivity;

    protected $guarded = [];
}
