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
        Schema::create('litecom_zones', function (Blueprint $table) {
            $table->id();
            $table->string('zone_id');
            $table->string('name');
            $table->string("connection");
            $table->integer('auto_scene')->default(1);
            $table->integer('auto_turn_on_before')->default(15);
            $table->integer('auto_turn_off_after')->default(15);
            $table->integer('active_scene')->default(0);
            $table->dateTime('manual_override_until')->nullable();
            $table->string('manual_override_source')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('litecom_zones');
    }
};
