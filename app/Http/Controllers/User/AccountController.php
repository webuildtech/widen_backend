<?php

namespace App\Http\Controllers\User;

use App\Data\User\Account\AccountBalanceTopUpData;
use App\Data\User\Account\AccountData;
use App\Data\User\Account\AccountPasswordChangeData;
use App\Data\User\Account\AccountUpdateData;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Services\Payments\MakeCommerceService;
use App\Services\Payments\PaymentService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class AccountController extends Controller
{
    public function __construct(
        protected UserService         $userService,
        protected MakeCommerceService $makeCommerceService,
        protected PaymentService      $paymentService,
    )
    {
    }

    public function show(): AccountData
    {
        return AccountData::from(auth()->user());
    }

    public function update(AccountUpdateData $data): AccountData
    {
        $user = $this->userService->update(auth()->user(), $data->all());

        return AccountData::from($user);
    }

    public function changePassword(AccountPasswordChangeData $data): JsonResponse
    {
        $this->userService->update(auth()->user(), ['password' => $data->password]);

        return response()->json();
    }

    public function topUpBalance(AccountBalanceTopUpData $data): JsonResponse
    {
        $user = auth()->user();

        $payment = $this->paymentService->createFromAmount($data->amount, $user);

        try {
            $url = $this->makeCommerceService->createTransaction($payment, request()->ip());

            return response()->json(['url' => $url], 201);
        } catch (RuntimeException $e) {
            $this->paymentService->cancel($payment->refresh(), PaymentStatus::CANCELLED);

            return response()->json([
                'message' => 'Atsiprašome, šiuo metu negalime susisiekti su mokėjimo paslaugų teikėju. Prašome pabandyti vėliau.'
            ], 500);
        }
    }
}
