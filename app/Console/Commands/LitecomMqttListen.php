<?php

namespace App\Console\Commands;

use App\Models\LitecomZone;
use Illuminate\Console\Command;
use Log;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use RuntimeException;
use Throwable;

class LitecomMqttListen extends Command
{
    protected $signature = 'litecom:mqtt-worker {connection : primary|secondary}';
    protected $description = 'Runs a dedicated MQTT worker for the given LITECOM connection (primary or secondary).';

    public function handle(): int
    {
        $connectionName = $this->argument('connection');

        if (!in_array($connectionName, ['primary', 'secondary'], true)) {
            $this->error('Connection must be "primary" or "secondary".');
            return self::FAILURE;
        }

        while (true) {
            $client = null;

            try {
                $cfg = config("litecom.connections.{$connectionName}");
                $host = $cfg['mqtt_host'] ?? null;
                $port = (int)($cfg['mqtt_port'] ?? 8883);
                $username = $cfg['mqtt_username'] ?? null;
                $password = $cfg['mqtt_password'] ?? null;

                if (!$host) {
                    throw new RuntimeException("MQTT host missing for {$connectionName}");
                }

                $clientId = "litecom-{$connectionName}-" . bin2hex(random_bytes(4));
                $client = new MqttClient($host, $port, $clientId, MqttClient::MQTT_3_1);

                $settings = (new ConnectionSettings)
                    ->setUsername($username)
                    ->setPassword($password)
                    ->setKeepAliveInterval(60)
                    ->setConnectTimeout(25)
                    ->setUseTls(true)
                    ->setTlsSelfSignedAllowed(true)
                    ->setTlsVerifyPeer(false)
                    ->setTlsVerifyPeerName(false);

                $client->connect($settings, true);

                $this->info("[$connectionName] Connected to MQTT {$host}:{$port}");
                Log::info("[MQTT] {$connectionName} connected {$host}:{$port}");

                $topics = [
                    '/zones/+/services/scene/activeScene',
                    'zones/+/services/scene/activeScene',
                ];

                foreach ($topics as $topic) {
                    $client->subscribe($topic, function (string $topic, string $message) use ($connectionName) {
                        $this->info("Received message on topic '{$topic}': {$message}");

                        try {
                            $trim = ltrim($topic, '/');
                            $parts = explode('/', $trim);
                            if (count($parts) >= 5
                                && $parts[0] === 'zones'
                                && $parts[2] === 'services'
                                && $parts[3] === 'scene'
                                && $parts[4] === 'activeScene'
                            ) {
                                $zoneId = $parts[1];
                                $activeScene = (int)trim($message);

                                $litecomZone = LitecomZone::whereZoneId($zoneId)
                                    ->where('connection', $connectionName)
                                    ->first();

                                if ($litecomZone) {
                                    if ($litecomZone->active_scene !== $activeScene) {
                                        $updateData = ['active_scene' => $activeScene];

                                        if ($activeScene === 0) {
                                            $updateData['manual_override_until'] = null;
                                            $updateData['manual_override_source'] = null;
                                        }

                                        $litecomZone->update($updateData);
                                    }

                                    Log::info("[MQTT] {$connectionName} zone {$zoneId} -> active_scene={$activeScene}");
                                } else {
                                    Log::warning("[MQTT] {$connectionName} zone {$zoneId} not found in DB (msg={$message})");
                                }
                            }
                        } catch (Throwable $e) {
                            Log::error("[MQTT] {$connectionName} handler error: " . $e->getMessage(), ['ex' => $e]);
                        }
                    }, MqttClient::QOS_AT_LEAST_ONCE);
                }

                $client->loop();
            } catch (Throwable $e) {
                $msg = "[MQTT] {$connectionName} disconnected/failure: " . $e->getMessage();
                $this->error($msg);
                Log::error($msg, ['ex' => $e]);

                try {
                    if ($client) {
                        $client->disconnect();
                    }
                } catch (Throwable $e2) {
                }

                $this->warn("[$connectionName] Reconnecting in 120s...");

                sleep(120);
            }
        }
    }
}

