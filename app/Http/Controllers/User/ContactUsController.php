<?php

namespace App\Http\Controllers\User;

use App\Data\User\ContactUsData;
use App\Http\Controllers\Controller;
use App\Mail\ContactUsMail;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContactUsController extends Controller
{
    public function store(ContactUsData $data): JsonResponse
    {
        if (env('MAIL_TO_ADDRESS')) {
            Mail::to(env('MAIL_TO_ADDRESS'))->send(new ContactUsMail($data));
        }

        return response()->json();
    }
}
