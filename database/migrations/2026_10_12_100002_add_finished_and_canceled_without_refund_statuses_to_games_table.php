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
            $table->enum('status', array_column(GameStatus::cases(), 'value'))
                ->default(GameStatus::PUBLISHED->value)
                ->change();
        });

        DB::table('games')
            ->where('status', GameStatus::PUBLISHED->value)
            ->where('end_time', '<=', now())
            ->update(['status' => GameStatus::FINISHED->value]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('games')
            ->where('status', GameStatus::FINISHED->value)
            ->update(['status' => GameStatus::PUBLISHED->value]);

        DB::table('games')
            ->where('status', GameStatus::CANCELED_WITHOUT_REFUND->value)
            ->update(['status' => GameStatus::CANCELED->value]);

        Schema::table('games', function (Blueprint $table) {
            $table->enum('status', [GameStatus::PUBLISHED->value, GameStatus::CANCELED->value])
                ->default(GameStatus::PUBLISHED->value)
                ->change();
        });
    }
};
