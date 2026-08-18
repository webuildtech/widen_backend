<?php

namespace App\Data\User\Games;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameFilterData extends Data
{
    public function __construct(
        #[Exists('court_types', 'id', withoutTrashed: true)]
        public int|Optional     $court_type_id,

        #[Date]
        public Carbon|Optional  $date_from,

        #[Date]
        public Carbon|Optional  $date_to,

        public bool|Optional    $only_available,
    )
    {
    }
}
