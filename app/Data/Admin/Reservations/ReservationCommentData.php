<?php

namespace App\Data\Admin\Reservations;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ReservationCommentData extends Data
{
    public function __construct(
        public ?string $comment,
    )
    {
    }
}
