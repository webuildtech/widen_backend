<?php

namespace App\Services\Media;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

class LogoManager
{
    public function handle(Model $model, Data $data): void
    {
        $fields = $data->only('logoFile', 'deleteLogo')->toArray();

        if (!empty($fields['logoFile'])) {
            $model->addMedia($fields['logoFile'])->toMediaCollection('logo');
        } elseif (!empty($fields['deleteLogo'])) {
            $model->clearMediaCollection('logo');
        }
    }
}
