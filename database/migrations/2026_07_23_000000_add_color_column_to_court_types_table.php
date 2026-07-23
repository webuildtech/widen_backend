<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Distinct hues so court types are easy to tell apart in the booking grid.
     * Deliberately avoids the slot-state colors (purple = yours, green = cart,
     * yellow = sale, gray = occupied).
     */
    private const COLORS = [
        'Tenisas | Vidus'  => '#2563eb', // blue
        'Tenisas | Laukas' => '#ea580c', // orange (outdoor/clay)
        'Badmintonas'      => '#db2777', // pink
        'Stalo tenisas'    => '#0d9488', // teal
    ];

    public function up(): void
    {
        Schema::table('court_types', function (Blueprint $table) {
            $table->string('color')->nullable()->after('note');
        });

        $now = now();

        foreach (self::COLORS as $name => $hex) {
            DB::table('court_types')->where('name', $name)->update([
                'color' => $hex,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('court_types', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
