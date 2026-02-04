<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\LitecomZones\LitecomZoneData;
use App\Data\Admin\LitecomZones\LitecomZoneListData;
use App\Data\Admin\LitecomZones\LitecomZoneOnData;
use App\Data\Admin\LitecomZones\LitecomZoneSelectOptionData;
use App\Data\Admin\LitecomZones\LitecomZoneUpdateData;
use App\Http\Controllers\Controller;
use App\Models\LitecomZone;
use App\Services\Litecom\LitecomService;
use Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LitecomZoneController extends Controller
{
    public function __construct(
        protected LitecomService $litecomService
    ) {
    }

    public function index()
    {
        $litecomZones = QueryBuilder::for(LitecomZone::class)
            ->allowedSorts([
                'name',
                'auto_scene',
                'auto_turn_on_before',
                'auto_turn_off_after',
                'active_scene',
                'manual_override_until',
                'updated_at'
            ])
            ->allowedFilters([
                'name',
                AllowedFilter::exact('auto_scene'),
                AllowedFilter::scope('updated_at_between'),
            ])
            ->defaultSort('name')
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return LitecomZoneListData::collect($litecomZones);
    }

    public function sync()
    {
        try {
            $this->litecomService->syncZones();

            return response()->json(['message' => __('litecom.zones.sync.success')]);
        } catch (\Exception $e) {
            Log::warning('Litecom zones sync failed: ' . $e->getMessage());

            return response()->json(['message' => __('litecom.unavailable_try_later')], 500);
        }
    }

    public function show(LitecomZone $litecomZone): LitecomZoneData
    {
        return LitecomZoneData::from($litecomZone);
    }

    public function update(LitecomZoneUpdateData $data, LitecomZone $litecomZone): LitecomZoneData
    {
        $litecomZone->update($data->all());

        return LitecomZoneData::from($litecomZone);
    }

    public function all()
    {
        return LitecomZoneSelectOptionData::collect(LitecomZone::query()->orderBy('name')->get());
    }

    public function on(LitecomZoneOnData $data, LitecomZone $litecomZone)
    {
        try {
            $this->litecomService->on($litecomZone, $data->scene, $data->manual_override_until);

            return response()->json(['message' => __('litecom.zones.on.success')]);
        } catch (\Exception $e) {
            Log::warning('Litecom zone ON failed: ' . $e->getMessage());

            return response()->json(['message' => __('litecom.unavailable_try_later')], 500);
        }
    }

    public function off(LitecomZone $litecomZone)
    {
        try {
            $this->litecomService->off($litecomZone);

            return response()->json(['message' => __('litecom.zones.off.success')]);
        } catch (\Exception $e) {
            Log::warning('Litecom zone OFF failed: ' . $e->getMessage());

            return response()->json(['message' => __('litecom.unavailable_try_later')], 500);
        }
    }
}
