<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum FeatureType: string
{
    case RESERVATION_PER_WEEK = 'reservations_per_week';
}
