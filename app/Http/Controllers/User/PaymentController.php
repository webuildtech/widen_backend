<?php

namespace App\Http\Controllers\User;

use App\Data\User\Payments\PaymentListData;
use App\Data\User\Payments\PaymentData;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessPaymentCallback;
use App\Models\Payment;
use App\Services\Payments\InvoiceService;
use App\Services\Payments\MakeCommerceService;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        protected MakeCommerceService $makeCommerceService,
        protected PaymentService      $paymentService,
        protected InvoiceService      $invoiceService
    )
    {
    }

    public function index()
    {
        $payments = auth()->user()->payments()
            ->whereStatus(PaymentStatus::PAID)
            ->orderBy('paid_at', 'desc')
            ->get();

        return PaymentListData::collect($payments);
    }

    public function callback(): JsonResponse
    {
        $values = request()->all(['json', 'mac']);

        if ($this->makeCommerceService->verify($values)) {
            $values = $this->makeCommerceService->extractData($values['json']);
            \Log::info('callback', $values);

            ProcessPaymentCallback::dispatch($values);
        }

        return response()->json();
    }

    public function validate(): PaymentData|JsonResponse
    {
        $values = request()->all(['json', 'mac']);

        if (!$this->makeCommerceService->verify($values)) {
            return response()->json(['error' => 'Jūs neturite teisių.'], 403);
        }

        $data = $this->makeCommerceService->extractData($values['json']);

        $payment = Payment::whereTransactionId($data['transaction'])->first();

        $payment = match ($data['status']) {
            'COMPLETED' => $this->paymentService->approve($payment),
            'CANCELLED' => $this->paymentService->cancel($payment, PaymentStatus::CANCELLED),
            'EXPIRED' => $this->paymentService->cancel($payment, PaymentStatus::EXPIRED),
            default => $payment
        };

        return PaymentData::from($payment);
    }

    public function downloadInvoice(Payment $payment)
    {
        $user = auth()->user();

        if ($user->id !== $payment->owner_id || $payment->owner_type !== 'user' || floatval($payment->paid_amount) === 0.0) {
            return response()->json(['error' => 'Veiksmas negalimas!'], 403);
        }

        if (!$payment->invoice_path) {
            $this->invoiceService->generate($payment);
        }

        return response()->download(storage_path('app' . $payment->invoice_path));
    }
}
