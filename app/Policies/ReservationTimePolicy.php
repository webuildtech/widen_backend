<?php

namespace App\Policies;

use App\Models\ReservationTime;
use App\Models\User;

class ReservationTimePolicy
{
    public function cancel(User $user, ReservationTime $reservationTime): bool
    {
        return $user->id === $reservationTime->reservation->user_id;
    }
}
