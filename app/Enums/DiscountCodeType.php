<?php

namespace App\Enums;

enum DiscountCodeType: string
{
    case PERCENT = 'percent';

    case FIXED = 'fixed';
}
