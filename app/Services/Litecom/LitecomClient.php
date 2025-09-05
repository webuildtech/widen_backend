<?php

namespace App\Services\Litecom;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class LitecomClient
{
    public function __construct(
        public readonly string $name,
        public readonly string $baseUrl,
        public readonly string $token,
        public readonly int    $timeout = 10,
        public readonly int    $retries = 2,
    )
    {
        if (!$this->baseUrl || !$this->token) {
            throw new RuntimeException("Litecom[{$this->name}] missing baseUrl or token.");
        }
    }

    public function getZones(): array
    {
        return $this->request('GET', "{$this->baseUrl}/zones");
    }

    public function getZoneActiveScene(string $zoneId): array
    {
        return $this->request('GET', "{$this->baseUrl}/zones/{$zoneId}/services/scene");
    }

    public function setZoneScene(string $zoneId, int $sceneNumber): array
    {
        if ($sceneNumber < 0 || $sceneNumber > 20) {
            throw new RuntimeException('sceneNumber must be between 0 and 20.');
        }

        $endpoint = "{$this->baseUrl}/zones/{$zoneId}/services/scene";
        $payload = ['activeScene' => $sceneNumber];

        return $this->request('PUT', $endpoint, $payload);
    }

    private function request(string $method, string $url, array $payload = []): array
    {
        $resp = Http::withToken($this->token)
            ->timeout($this->timeout)
            ->retry($this->retries, 250)
            ->withoutVerifying()
            ->acceptJson()
            ->{$method}($url, $payload);

        if ($resp->successful()) {
            $data = $resp->json();

            return is_array($data) ? $data : [];
        }

        throw new RuntimeException("Litecom[{$this->name}] {$method} {$url} failed [{$resp->status()}]: {$resp->body()}");
    }
}
