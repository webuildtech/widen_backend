<?php

namespace App\Data\Admin\Advertisements;

use App\Data\Core\Media\MediaData;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AdvertisementData extends Data
{
    public function __construct(
        public int        $id,

        public ?string    $primary_button_label,

        public ?string    $primary_button_to,

        public bool       $is_active,

        public ?MediaData $logo,
    )
    {
    }
}
