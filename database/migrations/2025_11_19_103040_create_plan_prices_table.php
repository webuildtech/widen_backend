<?php

use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->integer('grace_days')->default(0);
            $table->integer('periodicity')->unsigned()->nullable();
            $table->string('periodicity_type')->nullable();
            $table->decimal('previous_price')->nullable();
            $table->decimal('price')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::transaction(function () {
            Plan::whereIsDefault(false)->get()->each(fn($plan) => DB::table('plan_prices')->insert([
                'id' => $plan->id,
                'plan_id' => $plan->id,
                'price' => $plan->price,
                'periodicity' => 1,
                'periodicity_type' => PeriodicityType::Year,
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            Payment::where('paymentable_type', 'plan')->update(['paymentable_type' => 'planPrice']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_prices');
    }
};
