<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Dashboard\IncomeFilterData;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Court;
use App\Models\Group;
use App\Models\Plan;
use App\Models\User;
use App\Services\Payments\IncomeService;
use LucasDotVin\Soulbscription\Models\Subscription;

class DashboardController extends Controller
{
    public function __construct(
        protected IncomeService $incomeService,
    )
    {
    }

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
        return $this->incomeService->getIncomes();
    }

    public function incomesByInterval(IncomeFilterData $data): array
    {
        return $this->incomeService->getIncomesByInterval($data);
    }
}
