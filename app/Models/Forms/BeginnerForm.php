<?php

namespace App\Models\Forms;

use App\Models\BaseModel;

class BeginnerForm extends BaseModel
{
    protected $casts = [
        'groups' => 'array',
    ];
}
