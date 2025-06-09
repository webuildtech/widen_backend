<?php

namespace App\Data\Admin\Courts;

use App\Enums\CourtType;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CourtStoreData extends Data
{
    public function __construct(
        #[Rule(['max:255'])]
        public string                $name,

        #[Rule(['max:255'])]
        public ?string               $inside_name,

        public ?string               $description,

        public CourtType             $type,

        public bool|Optional         $active,

        #[Image, Max(20480)]
        public UploadedFile|Optional $logoFile,

        /** @var array<int> */
        public array|Optional|null   $intervals_ids,
    )
    {
        if ($this->intervals_ids === null) {
            $this->intervals_ids = [];
        }
    }

    public static function rules(): array
    {

        return [
            'intervals.*' => [
                'required',
                new Exists(
                    'intervals',
                    'id',
                    withoutTrashed: true,
                    where: fn(Builder $builder) => $builder->where('date_to', '>=', now()),
                )
            ],
        ];
    }
}
