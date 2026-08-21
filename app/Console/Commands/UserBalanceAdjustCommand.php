<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Users\AdjustUserBalance;
use Illuminate\Console\Command;

class UserBalanceAdjustCommand extends Command
{
    protected $signature = 'app:user-balance-adjust
        {adjustments* : One or more user_id:amount pairs, e.g. 129:91 762:-30.5}
        {--balance= : Force the resulting balance instead of before + amount}
        {--reason=Balanso korekcija : Reason stored on the balance entry}
        {--admin=1 : Admin the balance entry is attributed to}
        {--force : Skip the confirmation prompt}';

    protected $description = 'Writes balance corrections for users, optionally forcing the resulting balance';

    public function __construct(
        protected AdjustUserBalance $service,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $finalBalance = $this->option('balance') !== null ? (float)str_replace(',', '.', $this->option('balance')) : null;
        $adminId = (int)$this->option('admin');
        $reason = $this->option('reason');

        $adjustments = [];

        foreach ($this->argument('adjustments') as $adjustment) {
            [$userId, $amount] = array_pad(explode(':', $adjustment, 2), 2, null);

            $amount = str_replace(',', '.', (string)$amount);

            if (!is_numeric($userId) || !is_numeric($amount)) {
                $this->error("Invalid adjustment \"{$adjustment}\", expected user_id:amount (e.g. 129:91).");

                return self::FAILURE;
            }

            $user = User::find($userId);

            if (!$user) {
                $this->error("User {$userId} not found.");

                return self::FAILURE;
            }

            $adjustments[] = ['user' => $user, 'amount' => (float)$amount];
        }

        $this->table(
            ['User', 'Name', 'Balance now', 'Amount', 'Balance after'],
            collect($adjustments)->map(fn(array $adjustment) => [
                $adjustment['user']->id,
                $adjustment['user']->full_name,
                number_format($adjustment['user']->balance, 2),
                number_format($adjustment['amount'], 2),
                number_format($finalBalance ?? $adjustment['user']->balance + $adjustment['amount'], 2),
            ])->all()
        );

        if (!$this->option('force') && !$this->confirm('Apply these balance corrections?', true)) {
            $this->warn('Aborted.');

            return self::SUCCESS;
        }

        foreach ($adjustments as $adjustment) {
            $entry = $this->service->byAdmin(
                $adjustment['user'],
                $adjustment['amount'],
                $adminId,
                $reason,
                ['via' => 'console'],
                $finalBalance,
            );

            $this->info("User {$entry->user_id}: {$entry->before_balance} -> {$entry->after_balance} (entry #{$entry->id}, amount {$entry->amount})");
        }

        return self::SUCCESS;
    }
}
