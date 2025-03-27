<?php

namespace App\Http\Controllers\User;

use App\Data\User\Payments\ListPaymentData;
use App\Data\User\Payments\PaymentData;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessPaymentCallback;
use App\Models\Payment;
use App\Services\MakeCommerceService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        protected MakeCommerceService $makeCommerceService,
        protected PaymentService      $paymentService
    )
    {
    }

    public function index()
    {
        $payments = Payment::whereUserId(auth()->user()->id)
            ->whereStatus(PaymentStatus::PAID)
            ->orderBy('paid_at', 'desc')
            ->get();

        return ListPaymentData::collect($payments);
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
        if (auth()->user()->id !== $payment->user_id) {
            return response()->json(['error' => 'Veiksmas negalimas!'], 403);
        }

        if (!$payment->invoice_path) {
            $payment = $this->paymentService->generateInvoice($payment);
        }

        return response()->download(storage_path('app' . $payment->invoice_path));
    }
}
