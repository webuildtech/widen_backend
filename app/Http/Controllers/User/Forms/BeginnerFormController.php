<?php

namespace App\Http\Controllers\User\Forms;

use App\Data\User\Forms\BeginnerFormStoreData;
use App\Http\Controllers\Controller;
use App\Models\Forms\BeginnerForm;
use Illuminate\Http\JsonResponse;

class BeginnerFormController extends Controller
{
    public function __invoke(BeginnerFormStoreData $data): JsonResponse
    {
        $values = $data->toArray();
        $values['groups'] = [$values['groups']];

        BeginnerForm::create($values);

        return response()->json();
    }
}
