<?php

namespace App\Data\User\Advertisements;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AdvertisementData extends Data
{
    public function __construct(
        public ?string $primary_button_label,

        public ?string $primary_button_to,

        public ?string $logoUrl,
    )
    {
    }
}
