<?php

use App\Console\Commands\AutoPlansRenewCommand;
use App\Console\Commands\DetachIntervalsFromCourtsCommand;
use App\Console\Commands\ReservationDeleteCommand;
use App\Console\Commands\ReservationUnpaidReminderCommand;

Schedule::command(DetachIntervalsFromCourtsCommand::class)->daily();

Schedule::command(AutoPlansRenewCommand::class)->daily();

Schedule::command(ReservationDeleteCommand::class)->everyThirtyMinutes();

Schedule::command(ReservationUnpaidReminderCommand::class)->everyThirtyMinutes();

Schedule::command('sanctum:prune-expired --hours=24')->daily();

Schedule::command('auth:clear-resets')->everyFifteenMinutes();
