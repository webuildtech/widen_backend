<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum SubscriptionRenewalMethod: string
{
    case AUTO = 'auto';

    case LINK = 'link';
}
