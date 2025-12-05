<?php

namespace App\Http\Controllers\User\Forms;

use App\Data\User\Forms\CompanyFormStoreData;
use App\Http\Controllers\Controller;
use App\Mail\CompanyFormMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class CompanyFormController extends Controller
{
    public function __invoke(CompanyFormStoreData $data): JsonResponse
    {
        if (env('MAIL_TO_ADDRESS')) {
            Mail::to(env('MAIL_TO_ADDRESS'))->send(new CompanyFormMail($data));
        }

        return response()->json();
    }
}
