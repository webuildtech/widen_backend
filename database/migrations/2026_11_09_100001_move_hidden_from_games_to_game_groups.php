<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('game_groups', function (Blueprint $table) {
            $table->boolean('hidden')->default(false)->after('active');
        });

        if (Schema::hasColumn('games', 'hidden')) {
            DB::table('game_groups')
                ->whereIn('id', DB::table('games')->where('hidden', true)->whereNotNull('game_group_id')->distinct()->pluck('game_group_id'))
                ->update(['hidden' => true]);

            Schema::table('games', function (Blueprint $table) {
                $table->dropColumn('hidden');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('games', 'hidden')) {
            Schema::table('games', function (Blueprint $table) {
                $table->boolean('hidden')->default(false)->after('auto_cancel');
            });
        }

        DB::table('games')
            ->whereIn('game_group_id', DB::table('game_groups')->where('hidden', true)->pluck('id'))
            ->update(['hidden' => true]);

        Schema::table('game_groups', function (Blueprint $table) {
            $table->dropColumn('hidden');
        });
    }
};
