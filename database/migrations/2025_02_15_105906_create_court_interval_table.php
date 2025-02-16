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
        Schema::create('court_interval', function (Blueprint $table) {
            $table->foreignId('court_id')->constrained()->cascadeOnDelete();
            $table->foreignId('interval_id')->constrained()->cascadeOnDelete();
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_interval');
    }
};
