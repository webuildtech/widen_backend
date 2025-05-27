<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\PlanRepositoryInterface;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use Spatie\LaravelData\Data;

class PlanRepository extends BaseRepository implements PlanRepositoryInterface
{
    public function __construct(Plan $model)
    {
        parent::__construct($model);
    }

    public function create(Data $data): Model
    {
        $values = $data->toArray();

        $plan = $this->model->create([
            'name' => $values['name'],
            'type' => $values['type'],
            'price' => $values['price'],
            'cancel_before' => $values['cancel_before'],
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Month,
            'active' => $values['active'] ?? 0,
        ]);

        return $plan->refresh();
    }

    public function update(Model $plan, Data $data): Model
    {
        $values = $data->toArray();

        $plan->update($values);

        return $plan->refresh();
    }
}
