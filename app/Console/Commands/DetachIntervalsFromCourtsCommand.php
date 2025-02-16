<?php

namespace App\Console\Commands;

use App\Models\Interval;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DetachIntervalsFromCourtsCommand extends Command
{
    protected $signature = 'app:detach-intervals-from-courts-command';


    public function handle()
    {
        $intervals = Interval::where('date_to', '<', now())->pluck('id');

        DB::table('court_interval')->whereIn('interval_id', $intervals)->delete();
    }
}
