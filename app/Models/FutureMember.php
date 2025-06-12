<?php

namespace App\Models;

/**
 * @mixin IdeHelperFutureMember
 */
class FutureMember extends BaseModel
{
    protected $casts = [
        'services' => 'array',
        'days' => 'array',
        'times' => 'array',
    ];
}
