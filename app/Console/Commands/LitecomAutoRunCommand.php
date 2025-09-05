<?php

namespace App\Console\Commands;

use App\Services\Litecom\LightAutomationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LitecomAutoRunCommand extends Command
{
    protected $signature = 'litecom:auto-run {--dry-run}';
    protected $description = 'Automatically adjusts scenes for all zones based on reservations and auto rules.';

    public function handle(LightAutomationService $service): int
    {
        $dryRun = $this->option('dry-run');
        $now = Carbon::now();

        $result = $service->run($now, $dryRun);

        $this->info("Litecom auto-run @ {$now->toDateTimeString()}");
        $this->line(" Turned ON:  {$result['turnedOn']}");
        $this->line(" Turned OFF: {$result['turnedOff']}");
        $this->line(" Skipped:    {$result['skipped']}");

        return self::SUCCESS;
    }
}
