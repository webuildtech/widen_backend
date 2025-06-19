<?php

namespace App\Data\Admin\Invoices;

use App\Data\Core\Owners\OwnerData;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class InvoiceListData extends Data
{
    public function __construct(
        public int       $id,

        public int       $number,

        public Carbon    $date,

        public string    $owner_type,

        public OwnerData $owner,

        public float     $price,

        public float     $vat,

        public float     $price_with_vat,

        public Carbon    $updated_at,
    )
    {
    }
}
