<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->foreignId('game_group_id')
                ->nullable()
                ->after('court_type_id')
                ->constrained()
                ->nullOnDelete();
        });

        $this->moveExistingGamesToDefaultGroup();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['game_group_id']);
            $table->dropColumn('game_group_id');
        });
    }

    private function moveExistingGamesToDefaultGroup(): void
    {
        if (!DB::table('games')->whereNull('game_group_id')->whereNull('deleted_at')->exists()) {
            return;
        }

        $groupId = DB::table('game_groups')->insertGetId([
            'uuid' => (string) Str::orderedUuid(),
            'name' => json_encode(['lt' => 'Kiti pasižaidimai', 'en' => 'Other games'], JSON_UNESCAPED_UNICODE),
            'sort_order' => 0,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('games')->whereNull('game_group_id')->update(['game_group_id' => $groupId]);
    }
};
