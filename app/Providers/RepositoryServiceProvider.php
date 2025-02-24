<?php

namespace App\Providers;

use App\Interfaces\Repositories\Models\AdminRepositoryInterface;
use App\Interfaces\Repositories\Models\CourtRepositoryInterface;
use App\Interfaces\Repositories\Models\GroupRepositoryInterface;
use App\Interfaces\Repositories\Models\IntervalRepositoryInterface;
use App\Interfaces\Repositories\Models\PlanRepositoryInterface;
use App\Interfaces\Repositories\Models\UserRepositoryInterface;
use App\Repositories\Models\AdminRepository;
use App\Repositories\Models\CourtRepository;
use App\Repositories\Models\GroupRepository;
use App\Repositories\Models\IntervalRepository;
use App\Repositories\Models\PlanRepository;
use App\Repositories\Models\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(CourtRepositoryInterface::class, CourtRepository::class);
        $this->app->bind(IntervalRepositoryInterface::class, IntervalRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
    }
}
