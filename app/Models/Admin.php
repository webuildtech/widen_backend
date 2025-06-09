<?php

namespace App\Models;

use App\Models\Concerns\HasAdminScopes;
use App\Models\Concerns\HasDateScopes;
use App\Models\Concerns\LogsSystemActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperAdmin
 */
class Admin extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    use LogsSystemActivity;
    use HasDateScopes;
    use HasAdminScopes;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role(): Attribute
    {
        return Attribute::get(fn() => $this->getRoleNames()->first());
    }
}
