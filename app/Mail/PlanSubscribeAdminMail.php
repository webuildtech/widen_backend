<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\PlanPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;

class PlanSubscribeAdminMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public const ACTION_NEW = 'new';
    public const ACTION_RENEW = 'renew';
    public const ACTION_SWITCH = 'switch';

    public string $actionLabel;

    public string $periodicity;

    public function __construct(
        public Payment $payment,
        string         $action = self::ACTION_NEW,
        ?string        $previousPlanName = null,
    )
    {
        $this->actionLabel = match ($action) {
            self::ACTION_RENEW => 'Plano pratęsimas',
            self::ACTION_SWITCH => $previousPlanName
                ? "Plano keitimas (iš „{$previousPlanName}“)"
                : 'Plano keitimas',
            default => 'Naujas planas',
        };

        $this->periodicity = $this->formatPeriodicity($payment->paymentable);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Naujas plano užsakymas: ' . $this->payment->paymentable->plan->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.planSubscribeAdmin',
        );
    }

    private function formatPeriodicity(PlanPrice $planPrice): string
    {
        $unit = match ($planPrice->periodicity_type) {
            PeriodicityType::Year => 'metai',
            PeriodicityType::Month => 'mėn.',
            PeriodicityType::Week => 'sav.',
            PeriodicityType::Day => 'd.',
            default => mb_strtolower((string) $planPrice->periodicity_type),
        };

        return trim("$planPrice->periodicity $unit");
    }
}
