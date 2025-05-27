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
        Schema::create('reservation_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reservation_time_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('slot_start');
            $table->dateTime('slot_end');
            $table->decimal('price', 10)->default(0);
            $table->decimal('vat', 10)->default(0);
            $table->decimal('discount', 10)->default(0);
            $table->decimal('price_with_vat', 10)->default(0);
            $table->boolean('try_sell')->default(false);
            $table->boolean('is_refunded')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_slots');
    }
};
