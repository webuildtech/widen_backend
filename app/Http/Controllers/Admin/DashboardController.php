<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Dashboard\IncomeFilterData;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Court;
use App\Models\Group;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use LucasDotVin\Soulbscription\Models\Subscription;

class DashboardController extends Controller
{
    public function metrics(): array
    {
        return [
            'users' => User::count(),
            'subscriptions' => Subscription::count(),
            'admins' => Admin::count(),
            'courts' => Court::count(),
            'groups' => Group::count(),
            'plans' => Plan::count(),
        ];
    }

    public function incomes(): array
    {
        $payments = Payment::whereStatus(PaymentStatus::PAID);

        return [
            'today' => $payments->clone()->where('paid_at', '>=', now()->startOfDay())->sum('paid_amount'),
            'week' => $payments->clone()->where('paid_at', '>=', now()->startOfWeek())->sum('paid_amount'),
            'last_7_days' => $payments->clone()->where('paid_at', '>=', now()->subDays(7))->sum('paid_amount'),
            'month' => $payments->clone()->where('paid_at', '>=', now()->startOfMonth())->sum('paid_amount'),
            'last_30_days' => $payments->clone()->where('paid_at', '>=', now()->subDays(30))->sum('paid_amount'),
            'year' => $payments->clone()->where('paid_at', '>=', now()->startOfYear())->sum('paid_amount'),
            'total' => $payments->clone()->sum('paid_amount'),
        ];
    }

    public function incomesByInterval(IncomeFilterData $data): array
    {
        $payments = Payment::whereStatus(PaymentStatus::PAID);

        $payments->whereDate('paid_at', '>=', $data->date_from);

        if ($data->date_to instanceof Carbon) {
            $payments->whereDate('paid_at', '<=', $data->date_to);
        }

        return [
            'interval' => $payments->sum('paid_amount')
        ];
    }
}
