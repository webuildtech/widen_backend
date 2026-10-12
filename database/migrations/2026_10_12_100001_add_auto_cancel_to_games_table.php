<?php

use App\Enums\GameStatus;
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
        Schema::table('games', function (Blueprint $table) {
            $table->boolean('auto_cancel')->default(false)->after('capacity');
        });

        DB::table('games')
            ->where('status', GameStatus::PUBLISHED->value)
            ->where('start_time', '>', now())
            ->update(['auto_cancel' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('auto_cancel');
        });
    }
};
