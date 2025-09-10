<?php

namespace App\Services\Litecom;

use App\Models\LitecomZone;
use Carbon\Carbon;

class LitecomService
{
    public function __construct(
        protected LitecomManager $litecomManager,
    )
    {
    }

    public function syncZones(): void
    {
        $zones = $this->litecomManager->getZones();

        $zonesIds = [];
        foreach ($zones as $zone) {
            if ($zone['level'] === 'ROOM') {
                $zonesIds[] = $zone['id'];

                if (LitecomZone::query()->where('zone_id', $zone['id'])->doesntExist()) {
                    $currentZone = $this->litecomManager->getZoneActiveScene($zone['id'], $zone['_connection']);

                    LitecomZone::query()->create([
                        'zone_id' => $zone['id'],
                        'connection' => $zone['_connection'],
                        'name' => $zone['name'],
                        'active_scene' => $currentZone['activeScene'],
                    ]);
                }
            }
        }

        LitecomZone::query()->whereNotIn('zone_id', $zonesIds)->delete();
    }

    public function on(LitecomZone $litecomZone, int $scene, ?Carbon $date): LitecomZone
    {
        if ($litecomZone->active_scene !== $scene) {
            $this->litecomManager->setZoneScene($litecomZone->zone_id, $scene, $litecomZone->connection);;
        }

        $attrs = ['active_scene' => $scene];

        $hasOverride = $date instanceof Carbon && $date->isFuture();

        if ($hasOverride) {
            $attrs['manual_override_until']  = $date->setSeconds(0)->setMilliseconds(0);
            $attrs['manual_override_source'] = 'admin';
        } else {
            $attrs['manual_override_until']  = null;
            $attrs['manual_override_source'] = null;
        }

        $litecomZone->update($attrs);

        return $litecomZone->refresh();
    }
    public function off(LitecomZone $litecomZone): LitecomZone
    {
        if ($litecomZone->active_scene !== 0) {
            $this->litecomManager->setZoneScene($litecomZone->zone_id, 0, $litecomZone->connection);;
        }

        $litecomZone->update([
            'active_scene' => 0,
            'manual_override_until' => null,
            'manual_override_source' => null,
        ]);

        return $litecomZone->refresh();
    }
}
