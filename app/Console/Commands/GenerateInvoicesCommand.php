<?php

namespace App\Console\Commands;

use App\Mail\InvoiceGenerateMail;
use App\Models\Guest;
use App\Models\User;
use App\Services\Payments\InvoiceService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class GenerateInvoicesCommand extends Command
{
    protected $signature = 'app:generate-invoices-command';

    public function handle(InvoiceService $invoiceService): void
    {
        $interval = $this->getCurrentMonthInterval();
        $invoiceDate = now()->subMonthNoOverflow()->endOfMonth();

        User::where($this->filterEntitiesWithRelevantData($interval))
            ->get()
            ->each(fn($user) => $this->generateInvoice($user, $interval, $invoiceDate, $invoiceService));

        Guest::where($this->filterEntitiesWithRelevantData($interval))
            ->get()
            ->each(fn($guest) => $this->generateInvoice($guest, $interval, $invoiceDate, $invoiceService));
    }

    private function getCurrentMonthInterval(): array
    {
        return [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()];
    }

    private function filterEntitiesWithRelevantData(array $interval): Closure
    {
        return function (Builder $query) use ($interval) {
            $query->whereHas('reservations', fn($q) => $q->whereIsPaid(true)->whereBetween('end_time', $interval))
                ->orWhereHas('payments', fn($q) => $q->whereStatus('paid')->wherePaymentableType('plan')->whereBetween('paid_at', $interval));
        };
    }

    private function generateInvoice(User|Guest $entity, array $interval, Carbon $invoiceDate, InvoiceService $invoiceService): void
    {
        $reservation = $entity->reservations()->whereIsPaid(true)->whereBetween('end_time', $interval);
        $payments = $entity->payments()->whereStatus('paid')->wherePaymentableType('plan')->whereBetween('paid_at', $interval);

        $priceWithVat = $reservation->sum('price_with_vat') - $reservation->sum('refunded_amount') + $payments->sum('price_with_vat');

        if ($priceWithVat > 0) {
            $invoice = $invoiceService->create($entity, $invoiceDate, $priceWithVat);

            Mail::queue(new InvoiceGenerateMail($invoice));
        }
    }
}
