<?php

namespace App\Models\Concerns;

trait HasBalance
{
    public function getDeductedAmount(float $totalPrice): float
    {
        return max(0, min($this->balance, $totalPrice));
    }

    public function addBalance(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }

    public function deductBalance(float $amount): void
    {
        $this->balance -= $amount;
        $this->save();
    }
}
