<?php

namespace App\Http\Controllers\Admin\Users;

use App\Data\Admin\Users\UserBalanceEntryListData;
use App\Data\Admin\Users\UserBalanceEntryStoreData;
use App\Models\User;
use App\Services\Users\AdjustUserBalance;

class UserBalanceEntryController
{
    public function __construct(
        protected AdjustUserBalance $service,
    ) {
    }

    public function index(User $user)
    {
        return UserBalanceEntryListData::collect(
            $user->balanceEntries()
                ->with('admin')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function store(User $user, UserBalanceEntryStoreData $data)
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
