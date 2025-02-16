<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\PlanRepositoryInterface;
use App\Models\Plan;

class PlanRepository extends BaseRepository implements PlanRepositoryInterface
{
    public function __construct(Plan $model)
    {
        parent::__construct($model);
    }
}
