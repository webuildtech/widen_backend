<?php

use App\Console\Commands\AutoPlansRenewCommand;
use App\Console\Commands\DetachIntervalsFromCourtsCommand;
use App\Console\Commands\GenerateInvoicesCommand;
use App\Console\Commands\LitecomAutoRunCommand;
use App\Console\Commands\ReservationDeleteCommand;
use App\Console\Commands\ReservationUnpaidReminderCommand;

Schedule::command(DetachIntervalsFromCourtsCommand::class)->daily();

Schedule::command(AutoPlansRenewCommand::class)->daily();

Schedule::command(ReservationDeleteCommand::class)->everyThirtyMinutes();

Schedule::command(ReservationUnpaidReminderCommand::class)->everyThirtyMinutes();

Schedule::command(GenerateInvoicesCommand::class)->monthly()->at('05:00');

Schedule::command('sanctum:prune-expired --hours=24')->daily();

Schedule::command('auth:clear-resets')->everyFifteenMinutes();

Schedule::command(LitecomAutoRunCommand::class)->everyMinute();
