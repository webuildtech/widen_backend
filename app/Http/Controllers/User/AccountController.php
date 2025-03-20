<?php

namespace App\Http\Controllers\User;

use App\Data\User\Account\ChangeAccountPasswordData;
use App\Data\User\Account\TopUpAccountBalanceData;
use App\Data\User\Account\UpdateAccountData;
use App\Data\User\AccountData;
use App\Http\Controllers\Controller;
use App\Interfaces\Repositories\Models\UserRepositoryInterface;
use App\Services\MakeCommerceService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected MakeCommerceService     $makeCommerceService,
        protected PaymentService          $paymentService,
    )
    {
    }

    public function show(): AccountData
    {
        return AccountData::from(auth()->user());
    }

    public function update(UpdateAccountData $data): AccountData
    {
        $user = $this->userRepository->update(auth()->user(), $data);

        return AccountData::from($user);
    }

    public function changePassword(ChangeAccountPasswordData $data): JsonResponse
    {
        $user = auth()->user();

        $user->update(['password' => Hash::make($data->password)]);

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
