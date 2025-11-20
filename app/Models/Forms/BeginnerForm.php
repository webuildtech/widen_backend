<?php

namespace App\Models\Forms;

use App\Models\BaseModel;

/**
 * @mixin IdeHelperBeginnerForm
 */
class BeginnerForm extends BaseModel
{
    protected $casts = [
        'groups' => 'array',
    ];
}
