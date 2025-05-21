<?php

use App\Enums\CourtType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('courts', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('courts', function (Blueprint $table) {
            $table->enum('type', array_column(CourtType::cases(), 'value'))->default(CourtType::TENNIS)->after('description');
        });
    }

    public function down(): void
    {

    }
};
