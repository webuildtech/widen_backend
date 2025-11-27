<?php

namespace App\Services\Users;

use App\Enums\PaymentStatus;
use App\Jobs\SubscribeUserToNewsletter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $attributes): User
    {
        $attributes['password'] = Hash::make($attributes['password']);

        $user = User::create($attributes);
        $user->refresh();

        if ($user->agreed_newsletter) {
            SubscribeUserToNewsletter::dispatch($user->id);
        }

        return $user;
    }

    public function update(User $user, array $attributes): Model
    {
        if (isset($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        $user->update($attributes);

        return $user->fresh();
    }

    public function getBalanceByDay(User $user, Carbon $date): float
    {
        $date = $date->copy()->endOfDay();

        $paymentsQuery = $user->payments()
            ->whereStatus(PaymentStatus::PAID->value)
            ->where('paid_at', '<=', $date);

        $paidAmountFromBalance = (clone $paymentsQuery)
            ->whereNotNull('paymentable_type')
            ->sum('paid_amount_from_balance');

        $paidAmount = (clone $paymentsQuery)
            ->whereNull('paymentable_type')
            ->sum('paid_amount');

        $refundedAmount = $user->reservations()
            ->withTrashed()
            ->whereIsPaid(true)
            ->where('canceled_at', '<=', $date)
            ->sum('refunded_amount');

        $adminAddedAmount = $user->balanceEntries()
            ->where('created_at', '<=', $date)
            ->sum('amount');

        return (float)$paidAmount
            + (float)$refundedAmount
            + (float)$adminAddedAmount
            - (float)$paidAmountFromBalance;
    }
}
