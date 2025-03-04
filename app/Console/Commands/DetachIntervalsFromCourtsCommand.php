<?php

namespace App\Console\Commands;

use App\Models\Court;
use App\Models\Interval;
use App\Models\IntervalPrice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DetachIntervalsFromCourtsCommand extends Command
{
    protected $signature = 'app:detach-intervals-from-courts-command';


    public function handle()
    {
//        $intervals = Interval::where('date_to', '<', now())->pluck('id');
//
//        DB::table('court_interval')->whereIn('interval_id', $intervals)->delete();

        $courts = Court::first();

        $slots = [];

        $courts->intervals()->first()->prices->each(function (IntervalPrice $intervalPrice) use (&$slots) {
            $currentTime = strtotime($intervalPrice->start_time);
            $endTime = strtotime($intervalPrice->end_time);

            while ($currentTime < $endTime) {
                $nextTime = strtotime("+30 minutes", $currentTime);

                if ($nextTime <= $endTime) {
                    $slots[] = [
                        "day" => $intervalPrice->day,
                        "start_time" => date("H:i", $currentTime),
                        "end_time" => date("H:i", $nextTime),
                        "price" => $intervalPrice->price
                    ];
                }

                $currentTime = $nextTime;
            }
        });

        dd($slots);
    }
}
