<?php

namespace App\Services;

use App\Models\IntervalPrice;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class IntervalPriceService
{
    public function getPriceForUser(User $user, IntervalPrice $price): float
    {
        $specialPrice = $price->groups()
            ->where(function (Builder $builder) use ($user) {
                $builder->whereHas('users', fn(Builder $query) => $query->where('users.id', $user->id));

                if ($user->subscription) {
                    $builder->orWhereHas('plan', fn(Builder $query) =>
                        $query->where('plans.id', $user->subscription->plan->plan_id)
                    );
                }
            })
            ->pluck('price')
            ->min();

        return floatval($specialPrice ?? $price->price);
    }
}
