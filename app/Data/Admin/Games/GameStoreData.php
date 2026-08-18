<?php

namespace App\Data\Admin\Games;

use App\Models\Court;
use App\Support\RegexPatterns;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameStoreData extends Data
{
    public function __construct(
        #[Exists('court_types', 'id', withoutTrashed: true)]
        public int                   $court_type_id,

        /**
         * One game is always one court; picking several here creates one game per court.
         *
         * @var array<int>
         */
        #[Min(1)]
        public array                 $courts_ids,

        #[Date]
        public Carbon                $date,

        #[Regex(RegexPatterns::TIME_HALF_HOUR)]
        public string                $start_time,

        #[Regex(RegexPatterns::TIME_HALF_HOUR)]
        public string                $end_time,

        public int                   $capacity,

        public float                 $price_with_vat,

        public array|Optional|null   $title,

        public array|Optional|null   $description,

        #[Image, Max(20480)]
        public UploadedFile|Optional $photoFile,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'capacity' => ['required', Rule::in(config('games.capacities'))],
            'courts_ids.*' => ['required', 'integer', Rule::exists('courts', 'id')->whereNull('deleted_at')],
            'price_with_vat' => ['required', 'numeric', 'min:0'],
        ];
    }

    public static function messages(): array
    {
        return [
            'start_time.required' => __('validation.availability.start_time.required'),
            'start_time.regex' => __('validation.availability.start_time.format'),

            'end_time.required' => __('validation.availability.end_time.required'),
            'end_time.regex' => __('validation.availability.end_time.format'),

            'price_with_vat.required' => __('validation.availability.price.required'),
            'price_with_vat.min' => __('validation.availability.price.min'),
        ];
    }

    public static function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $startTime = $validator->getValue('start_time');
            $endTime = $validator->getValue('end_time');

            if ($startTime && $endTime && $startTime >= $endTime) {
                $validator->errors()->add('end_time', __('validation.availability.slot.end_after_start'));
            }

            $courtTypeId = $validator->getValue('court_type_id');
            $courtsIds = $validator->getValue('courts_ids');

            if (!$courtTypeId || !is_array($courtsIds)) {
                return;
            }

            $matching = Court::whereIn('id', $courtsIds)->where('court_type_id', $courtTypeId)->count();

            if ($matching !== count(array_unique($courtsIds))) {
                $validator->errors()->add('courts_ids', __('validation.games.courts_court_type'));
            }
        });
    }
}
