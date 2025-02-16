<?php

use App\Console\Commands\DetachIntervalsFromCourtsCommand;

Schedule::command(DetachIntervalsFromCourtsCommand::class)->daily();
