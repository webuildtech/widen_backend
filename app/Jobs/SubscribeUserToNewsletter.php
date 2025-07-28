<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\OmnisendService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SubscribeUserToNewsletter implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $userId,
    )
    {
    }

    public function handle(OmnisendService $omnisendService): void
    {
        $user = User::findOrFail($this->userId);

        $omnisendService->createContactByUser($user);
    }
}
