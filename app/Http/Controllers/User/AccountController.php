<?php

namespace App\Http\Controllers\User;

use App\Data\User\Account\ChangeAccountPasswordData;
use App\Data\User\Account\TopUpAccountBalanceData;
use App\Data\User\Account\UpdateAccountData;
use App\Data\User\AccountData;
use App\Http\Controllers\Controller;
use App\Services\Payments\MakeCommerceService;
use App\Services\Payments\PaymentService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

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

    public function update(UpdateAccountData $data): AccountData
    {
        $user = $this->userService->update(auth()->user(), $data->all());

        return AccountData::from($user);
    }

    public function changePassword(ChangeAccountPasswordData $data): JsonResponse
    {
        $this->userService->update(auth()->user(), ['password' => $data->password]);

        return response()->json();
    }

    public function topUpBalance(TopUpAccountBalanceData $data): JsonResponse
    {
        $user = auth()->user();

        $payment = $this->paymentService->createFromAmount($data->amount, $user);

        $url = $this->makeCommerceService->createTransaction($payment, $user->email, request()->ip());

        return response()->json(['url' => $url], 201);
    }
}
