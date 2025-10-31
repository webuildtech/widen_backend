<?php

namespace App\Services\Users;

use App\Models\User;
use App\Models\UserBalanceEntry;
use Illuminate\Support\Facades\DB;

class AdjustUserBalance
{
    public function byAdmin(User $user, float $amount, int $adminId, ?string $reason = null, array $meta = []): UserBalanceEntry
    {
        return $this->write($user, $amount, 'admin_adjustment', $reason, $meta, $adminId);
    }

    public function byUserPayment(User $user, float $amount, ?string $reason = 'User payment', array $meta = []): UserBalanceEntry
    {
        return $this->write($user, $amount, 'user_payment', $reason, $meta);
    }

    private function write(User $user, float $amount, string $source, ?string $reason, array $meta, ?int $adminId = null): UserBalanceEntry
    {
        return DB::transaction(function () use ($user, $amount, $source, $reason, $meta, $adminId) {
            $user = User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();

            $before = $user->balance;
            $after = $before + $amount;

            $user->forceFill(['balance' => $after])->save();

            return UserBalanceEntry::create([
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'amount' => $amount,
                'before_balance' => $before,
                'after_balance' => $after,
                'reason' => $reason,
                'source' => $source,
                'meta' => array_merge([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ], $meta),
            ]);
        });
    }
}
