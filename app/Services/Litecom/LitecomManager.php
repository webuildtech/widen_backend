<?php

namespace App\Services\Litecom;

use Log;
use RuntimeException;

class LitecomManager
{
    /** @var array<string, LitecomClient> */
    private array $clients;

    public function __construct(private readonly array $config)
    {
        $this->clients = [];

        foreach ($config['connections'] as $name => $connection) {
            try {
                $this->clients[$name] = new LitecomClient(
                    name: $name,
                    baseUrl: $connection['base_url'] ?? '',
                    token: $connection['token'] ?? '',
                    timeout: $connection['timeout'] ?? 10,
                    retries: $connection['retries'] ?? 2,
                );
            } catch (\Throwable $e) {
                Log::error('Litecom connection error: ' . $e->getMessage());
            }
        }
    }

    public function connection(?string $name = null): LitecomClient
    {
        $name ??= ($this->config['default'] ?? array_key_first($this->clients));

        if (!isset($this->clients[$name])) {
            throw new RuntimeException("Litecom connection '{$name}' unregistered.");
        }

        return $this->clients[$name];
    }

    public function getZones(?string $connection = null, bool $mergeAll = false): array
    {
        if ($connection && !$mergeAll) {
            $client = $this->connection($connection);

            $zones = $client->getZones();

            return $this->tagZonesWithConnection($zones, $client->name);
        }

        $all = [];
        foreach (array_keys($this->clients) as $name) {
            $client = $this->connection($name);
            $part = $this->tagZonesWithConnection($client->getZones(), $client->name);

            $all = array_merge($all, $part);
        }

        return $all;
    }

    public function getZoneActiveScene(string $zoneId, ?string $connection = null): array
    {
        $client = $connection ? $this->connection($connection) : $this->resolveClientByZone($zoneId);

        return $client->getZoneActiveScene($zoneId);
    }

    public function setZoneScene(string $zoneId, int $sceneNumber, ?string $connection = null): array
    {
        $client = $connection ? $this->connection($connection) : $this->resolveClientByZone($zoneId);

        return $client->setZoneScene($zoneId, $sceneNumber);
    }

    private function tagZonesWithConnection(array $zones, string $connectionName): array
    {
        return array_map(function ($z) use ($connectionName) {
            if (is_array($z)) $z['_connection'] = $connectionName;

            return $z;
        }, $zones);
    }

    private function resolveClientByZone(string $zoneId): LitecomClient
    {
        $index = $this->buildZoneIndex();

        $connection = $index[$zoneId] ?? null;

        if (!$connection) {
            $index = $this->buildZoneIndex();

            $connection = $index[$zoneId] ?? null;
        }

        if (!$connection || !isset($this->clients[$connection])) {
            throw new RuntimeException("Unable to determine which controller contains zone {$zoneId}.");
        }

        return $this->clients[$connection];
    }

    private function buildZoneIndex(): array
    {
        $map = [];

        foreach ($this->clients as $name => $client) {
            try {
                $zones = $client->getZones();

                foreach ($zones as $zone) {
                    if (is_array($zone) && isset($zone['id'])) {
                        $map[$zone['id']] = $name;
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        return $map;
    }
}
