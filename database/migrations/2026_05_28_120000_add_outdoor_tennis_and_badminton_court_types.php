<?php

use App\Enums\Day;
use App\Services\Slots\PlanCourtTypeRuleSlotService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const COURT_TYPES = [
        'Tenisas | Vidus' => 1,
        'Tenisas | Laukas' => 2,
        'Badmintonas' => 3,
        'Stalo tenisas' => 4,
    ];

    public function up(): void
    {
        Schema::table('court_types', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('name');
        });

        $now = now();

        // Rename the legacy "Tenisas" row in place so existing courts/rules keep their court_type_id.
        DB::table('court_types')->where('name', 'Tenisas')->update([
            'name' => 'Tenisas | Vidus',
            'updated_at' => $now,
        ]);

        foreach (self::COURT_TYPES as $name => $sortOrder) {
            $existing = DB::table('court_types')->where('name', $name)->first();

            if ($existing) {
                DB::table('court_types')->where('id', $existing->id)->update([
                    'sort_order' => $sortOrder,
                    'updated_at' => $now,
                ]);
            } else {
                DB::table('court_types')->insert([
                    'name' => $name,
                    'sort_order' => $sortOrder,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->backfillPlanRules($now);
    }

    public function down(): void
    {
        $newTypeIds = DB::table('court_types')
            ->whereIn('name', ['Tenisas | Laukas', 'Badmintonas'])
            ->pluck('id');

        $ruleIds = DB::table('plan_court_type_rules')
            ->whereIn('court_type_id', $newTypeIds)
            ->pluck('id');

        DB::table('plan_court_type_rule_slots')->whereIn('plan_court_type_rule_id', $ruleIds)->delete();
        DB::table('plan_court_type_rules')->whereIn('id', $ruleIds)->delete();
        // Cascades to any courts assigned to these types via the court_type_id foreign key.
        DB::table('court_types')->whereIn('id', $newTypeIds)->delete();

        DB::table('court_types')->where('name', 'Tenisas | Vidus')->update(['name' => 'Tenisas']);

        Schema::table('court_types', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }

    private function backfillPlanRules(Carbon $now): void
    {
        // The new types mirror whatever each plan already has configured for the tennis type.
        $tennisTypeId = DB::table('court_types')->where('name', 'Tenisas | Vidus')->value('id');

        $courtTypeIds = DB::table('court_types')->pluck('id');
        $planIds = DB::table('plans')->pluck('id');

        foreach ($planIds as $planId) {
            $sourceRule = $tennisTypeId
                ? DB::table('plan_court_type_rules')
                    ->where('plan_id', $planId)
                    ->where('court_type_id', $tennisTypeId)
                    ->first()
                : null;

            $sourceSlots = $sourceRule
                ? DB::table('plan_court_type_rule_slots')
                    ->where('plan_court_type_rule_id', $sourceRule->id)
                    ->whereNull('deleted_at')
                    ->get(['day', 'start_time', 'end_time'])
                : collect();

            foreach ($courtTypeIds as $courtTypeId) {
                $ruleExists = DB::table('plan_court_type_rules')
                    ->where('plan_id', $planId)
                    ->where('court_type_id', $courtTypeId)
                    ->exists();

                if ($ruleExists) {
                    continue;
                }

                $ruleId = DB::table('plan_court_type_rules')->insertGetId([
                    'plan_id' => $planId,
                    'court_type_id' => $courtTypeId,
                    'max_days_in_advance' => $sourceRule->max_days_in_advance ?? 14,
                    'cancel_hours_before' => $sourceRule->cancel_hours_before ?? 48,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $slots = [];

                if ($sourceSlots->isNotEmpty()) {
                    foreach ($sourceSlots as $slot) {
                        $slots[] = [
                            'plan_court_type_rule_id' => $ruleId,
                            'day' => $slot->day,
                            'start_time' => $slot->start_time,
                            'end_time' => $slot->end_time,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                } else {
                    foreach (Day::cases() as $day) {
                        foreach (PlanCourtTypeRuleSlotService::DEFAULT_SLOTS as $startTime => $endTime) {
                            $slots[] = [
                                'plan_court_type_rule_id' => $ruleId,
                                'day' => $day->value,
                                'start_time' => $startTime,
                                'end_time' => $endTime,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }
                    }
                }

                DB::table('plan_court_type_rule_slots')->insert($slots);
            }
        }
    }
};
