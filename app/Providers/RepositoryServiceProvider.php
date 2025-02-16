<?php

namespace App\Providers;

use App\Interfaces\Repositories\Models\CourtRepositoryInterface;
use App\Interfaces\Repositories\Models\IntervalRepositoryInterface;
use App\Interfaces\Repositories\Models\PlanRepositoryInterface;
use App\Repositories\Models\CourtRepository;
use App\Repositories\Models\IntervalRepository;
use App\Repositories\Models\PlanRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CourtRepositoryInterface::class, CourtRepository::class);
        $this->app->bind(IntervalRepositoryInterface::class, IntervalRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
    }
}
