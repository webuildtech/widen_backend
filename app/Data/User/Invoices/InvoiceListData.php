<?php

namespace App\Data\User\Invoices;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class InvoiceListData extends Data
{
    public function __construct(
        public int    $id,

        public Carbon $date,

        public float  $price,

        public float  $vat,

        public float  $price_with_vat,
    )
    {
    }
}
