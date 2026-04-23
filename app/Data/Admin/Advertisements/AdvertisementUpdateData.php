<?php

namespace App\Data\Admin\Advertisements;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Accepted;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AdvertisementUpdateData extends Data
{
    public function __construct(
        #[Max(255)]
        public Optional|string|null  $primary_button_label,

        #[Max(255)]
        public Optional|string|null  $primary_button_to,

        public Optional|bool         $is_active,

        #[Image, Max(20480)]
        public UploadedFile|Optional $logoFile,

        #[Accepted]
        public bool|Optional         $deleteLogo,
    )
    {
    }
}
