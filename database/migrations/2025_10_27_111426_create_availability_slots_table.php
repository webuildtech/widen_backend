<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('availability_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_type_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('day', array_column(App\Enums\Day::cases(), 'value'));
            $table->string('start_time', 5);
            $table->string('end_time', 5);
            $table->boolean('is_reserved')->default(false);
            $table->boolean('is_blocked')->default(false);

            $table->unique(['court_id', 'date', 'start_time'], 'slots_uniq_court_date_start');
            $table->index(['court_id', 'date'], 'slots_idx_court_date');
            $table->index(['court_type_id', 'date'], 'slots_idx_type_date');
            $table->index(['date', 'court_type_id'], 'slots_idx_date_type');
            $table->index('day', 'slots_idx_day');
            $table->index(['court_id', 'day'], 'slots_idx_court_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability_slots');
    }
};
