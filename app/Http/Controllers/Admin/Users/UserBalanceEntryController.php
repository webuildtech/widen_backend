<?php

namespace App\Http\Controllers\Admin\Users;

use App\Data\Admin\Users\UserBalanceEntryListData;
use App\Data\Admin\Users\UserBalanceEntryStoreData;
use App\Models\User;
use App\Models\UserBalanceEntry;
use App\Services\Users\AdjustUserBalance;
use Spatie\QueryBuilder\QueryBuilder;

class UserBalanceEntryController
{
    public function __construct(
        protected AdjustUserBalance $service,
    ) {
    }

    public function index()
    {
        $usersBalanceEntries = QueryBuilder::for(UserBalanceEntry::class)
            ->with(['admin', 'user'])
            ->allowedSorts([
                'user_id',
                'admin_id',
                'amount',
                'before_balance',
                'after_balance',
                'created_at'
            ])
            ->allowedFilters([
                'user.first_name',
                'user.last_name',
                'user.email',
                'user.phone',
            ])
            ->defaultSort('-created_at')
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return UserBalanceEntryListData::collect($usersBalanceEntries);
    }

    public function allByUser(User $user)
    {
        return UserBalanceEntryListData::collect(
            $user->balanceEntries()
                ->with('admin')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function storeByUser(User $user, UserBalanceEntryStoreData $data)
    {
        $this->service->byAdmin(
            $user,
            $data->amount,
            auth()->user()->id,
            $data->reason,
        );

        return [];
    }
}
