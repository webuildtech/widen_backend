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

        $paidAmount = $user->payments()
            ->whereStatus(PaymentStatus::PAID->value)
            ->where('paid_at', '<=', $date)
            ->sum('paid_amount');

        $invoiceAmount = $user->invoices()
            ->where('date', '<=', $date)
            ->sum('price_with_vat');

        return (float)$paidAmount - (float)$invoiceAmount;
    }

    /**
     * Real user balance (excluding the effect of admin-gifted money).
     *
     * getBalanceByDay() returns (real payments - invoices). When gifted money
     * is spent, the invoice grows but there is no matching payment (it was paid
     * from balance), which produces a phantom negative balance. Here we add back
     * the portion of invoices that was covered by the gift, so that neither the
     * spent nor the unused gift distorts the real balance.
     */
    public function getRealBalanceByDay(User $user, Carbon $date): float
    {
        $date = $date->copy()->endOfDay();

        $invoiceAmount = (float)$user->invoices()
            ->where('date', '<=', $date)
            ->sum('price_with_vat');

        $giftedAmount = (float)$user->balanceEntries()
            ->where('source', 'admin_adjustment')
            ->where('created_at', '<=', $date)
            ->sum('amount');

        $usedGift = max(0, min($giftedAmount, $invoiceAmount));

        return $this->getBalanceByDay($user, $date) + $usedGift;
    }
}
