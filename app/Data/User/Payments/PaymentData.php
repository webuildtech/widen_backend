<?php

namespace App\Data\User\Payments;

use App\Data\Core\Owners\OwnerData;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PaymentData extends Data
{
    public function __construct(
        public PaymentStatus $status,

        public ?string $type,

        public OwnerData $owner,

        public ?float $balance
    )
    {
    }

    public static function fromModel(Payment $payment): self
    {
        return new self(
            $payment->status,
            $payment->paymentable_type,
            OwnerData::from($payment->owner),
            $payment->owner->balance ?? null,
        );
    }
}
