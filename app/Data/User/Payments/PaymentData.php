<?php

namespace App\Data\User\Payments;

use App\Models\Payment;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PaymentData extends Data
{
    public function __construct(
        public string $status,

        public ?string $type,

        public string $email,
    )
    {
    }

    public static function fromModel(Payment $payment): self
    {
        return new self(
            $payment->status,
            $payment->paymentable_type,
            $payment->user ? $payment->user->email : $payment->paymentable->guest_email
        );
    }
}
