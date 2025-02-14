<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

trait LogoTrait
{
    private function saveLogo(Model $model, Data $data): void
    {
        $data = $data->only('logoFile', 'deleteLogo')->toArray();

        if (isset($data['logoFile'])) {
            $model->addMedia($data['logoFile'])->toMediaCollection('logo');
        } elseif (isset($data['deleteLogo']) && $data['deleteLogo']) {
            $model->clearMediaCollection('logo');
        }
    }
}
