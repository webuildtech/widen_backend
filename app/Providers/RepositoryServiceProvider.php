<?php

namespace App\Providers;

use App\Interfaces\Repositories\Models\CourtRepositoryInterface;
use App\Interfaces\Repositories\Models\IntervalRepositoryInterface;
use App\Repositories\Models\CourtRepository;
use App\Repositories\Models\IntervalRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CourtRepositoryInterface::class, CourtRepository::class);
        $this->app->bind(IntervalRepositoryInterface::class, IntervalRepository::class);
    }
}
