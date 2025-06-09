<?php

namespace App\Data\Core\Media;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class MediaData extends Data
{
    public function __construct(
        #[MapOutputName('name')]
        public string $file_name,

        public int    $size,

        #[MapOutputName('url')]
        public string $original_url
    )
    {
    }
}
