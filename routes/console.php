<?php

use App\Console\Commands\DetachIntervalsFromCourtsCommand;

Schedule::command(DetachIntervalsFromCourtsCommand::class)->daily();

Schedule::command('sanctum:prune-expired --hours=24')->daily();

Schedule::command('auth:clear-resets')->everyFifteenMinutes();

