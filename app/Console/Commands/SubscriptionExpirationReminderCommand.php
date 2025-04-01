<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpirationReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use LucasDotVin\Soulbscription\Models\Subscription;

class SubscriptionExpirationReminderCommand extends Command
{
    protected $signature = 'app:subscription-expiration-reminder-command';

    public function handle()
    {
        Subscription::whereDate('expired_at', now()->addDays(7))->get()
            ->each(fn (Subscription $subscription) => Mail::queue(new SubscriptionExpirationReminderMail($subscription->subscriber, 7)));

        Subscription::whereDate('expired_at', now()->addDay())->get()
            ->each(fn (Subscription $subscription) => Mail::queue(new SubscriptionExpirationReminderMail($subscription->subscriber, 1)));
    }
}
