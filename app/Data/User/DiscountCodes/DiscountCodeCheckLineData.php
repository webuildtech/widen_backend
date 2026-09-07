<?php

namespace App\Data\User\DiscountCodes;

use App\Models\Court;
use App\Models\CourtType;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DiscountCodeCheckLineData extends Data
{
    public const STARTS_AT_FORMAT = 'Y-m-d H:i';

    public function __construct(
        #[Exists(Court::class, column: 'id', withoutTrashed: true)]
        public ?int    $court_id,

        #[Exists(CourtType::class, column: 'id', withoutTrashed: true)]
        public ?int    $court_type_id,

        #[DateFormat(self::STARTS_AT_FORMAT)]
        #[WithCast(DateTimeInterfaceCast::class, format: self::STARTS_AT_FORMAT)]
        public ?Carbon $starts_at,
    )
    {
    }
}
