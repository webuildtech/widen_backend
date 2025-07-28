<?php

namespace App\Http\Controllers\User;

use App\Data\User\Newsletter\NewsletterSubscribeData;
use App\Http\Controllers\Controller;
use App\Services\OmnisendService;
use Illuminate\Http\JsonResponse;

class NewsletterController extends Controller
{
    public function __construct(
        protected OmnisendService $omnisendService,
    )
    {
    }

    public function __invoke(NewsletterSubscribeData $data): JsonResponse
    {
        $this->omnisendService->createContactByEmail($data->email);

        return response()->json(['message' => 'Jūs sėkmingai užsiprenumeravote naujienlaiškį.']);
    }
}
