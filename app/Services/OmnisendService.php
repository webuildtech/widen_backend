<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class OmnisendService
{
    protected string $baseUrl = 'https://api.omnisend.com/v5';

    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.omnisend.api_key');
    }

    protected function client(): PendingRequest
    {
        return Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
            'Content-Type' => 'application/json',
        ]);
    }

    public function createContactByUser(User $user): array
    {
        $response = $this->client()->post("{$this->baseUrl}/contacts", [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'birthdate' => $user->birthday,
            'identifiers' => [
                [
                    'type' => 'email',
                    'id' => $user->email,
                    'channels' => [
                        'email' => [
                            'status' => 'subscribed'
                        ]
                    ]
                ]
            ]
        ]);

        return $response->json();
    }

    public function createContactByEmail(string $email): array
    {
        $response = $this->client()->post("{$this->baseUrl}/contacts", [
            'identifiers' => [
                [
                    'type' => 'email',
                    'id' => $email,
                    'channels' => [
                        'email' => [
                            'status' => 'subscribed'
                        ]
                    ]
                ]
            ]
        ]);

        return $response->json();
    }
}
