<?php

namespace App\Http\Controllers\User;

use App\Data\User\Payments\PaymentData;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\MakeCommerceService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Log;

class PaymentController extends Controller
{
    public function __construct(
        protected MakeCommerceService $makeCommerceService,
        protected PaymentService      $paymentService
    )
    {
    }

    public function callback(): JsonResponse
    {
        $data = request()->get('json');
        $mac = request()->get('mac');

        if (!$data || !$mac || !$this->makeCommerceService->verify($data, $mac)) {
            return response()->json(['error' => 'Jūs neturite teisių.'], 403);
        }

        $data = $this->makeCommerceService->extractData($data);
        Log::info('callback validate', $data);

        return response()->json();
    }

    public function validate(): PaymentData|JsonResponse
    {
        $data = request()->get('json');
        $mac = request()->get('mac');

        if (!$data || !$mac || !$this->makeCommerceService->verify($data, $mac)) {
            return response()->json(['error' => 'Jūs neturite teisių.'], 403);
        }

        $data = $this->makeCommerceService->extractData($data);
        Log::info('validate', $data);

        $payment = Payment::whereTransactionId($data['transaction'])->first();

        $payment = match ($data['status']) {
            'COMPLETED' => $this->paymentService->approve($payment),
            'CANCELLED' => $this->paymentService->cancel($payment, PaymentStatus::CANCELLED),
            'EXPIRED' => $this->paymentService->cancel($payment, PaymentStatus::EXPIRED),
            default => $payment
        };

        return PaymentData::from($payment);
    }
}
