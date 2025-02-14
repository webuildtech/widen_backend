<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\CourtRepositoryInterface;
use App\Models\Court;
use App\Traits\LogoTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

class CourtRepository extends BaseRepository implements CourtRepositoryInterface
{
    use LogoTrait;

    public function __construct(Court $model)
    {
        parent::__construct($model);
    }

    public function create(Data $data): Court
    {
        $court = $this->model->create($this->getData($data, ['logoFile']));

        $this->saveLogo($court, $data);

        return $court->refresh();
    }

    public function update(Model $model, Data $data): Model
    {
        $model->update($this->getData($data, ['logoFile', 'deleteLogo']));

        $this->saveLogo($model, $data);

        return $model->fresh();
    }
}
