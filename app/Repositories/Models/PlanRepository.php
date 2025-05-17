<?php

namespace App\Repositories\Models;

use App\Enums\FeatureType;
use App\Interfaces\Repositories\Models\PlanRepositoryInterface;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use LucasDotVin\Soulbscription\Models\Feature;
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

        $this->syncFeatures($plan, [
            FeatureType::RESERVATION_PER_WEEK->value => $values['reservations_per_week']
        ]);

        return $plan->refresh();
    }

    public function update(Model $plan, Data $data): Model
    {
        $values = $data->toArray();
        $features = [];

        if (isset($values['reservations_per_week'])) {
            $features[ FeatureType::RESERVATION_PER_WEEK->value] = $values['reservations_per_week'];
            unset($values['reservations_per_week']);
        }

        $plan->update($values);
        $this->syncFeatures($plan, $features);

        return $plan->refresh();
    }

    private function syncFeatures(Plan $plan, array $features): void
    {
        foreach ($features as $key => $value) {
            $feature = Feature::whereName($key)->first();

            $plan->features()->sync([$feature->id => ['charges' => $value]]);
        }
    }
}
