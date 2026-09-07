<?php

namespace App\Data\Admin\GameGroups;

use App\Support\Validation\Translatable;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameGroupStoreData extends Data
{
    public function __construct(
        #[Translatable]
        public array                 $name,

        #[Translatable(required: false, max: 5000)]
        public array|Optional|null   $description,

        #[IntegerType, Min(0)]
        public int|Optional          $sort_order,

        public bool|Optional         $active,

        #[Image, Max(20480)]
        public UploadedFile|Optional $photoFile,
    )
    {
    }
}
