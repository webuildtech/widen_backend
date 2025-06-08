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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->morphs('owner');

            $table->foreignId('reservation_group_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('delete_after_failed_payment')->default(true);

            $table->foreignId('court_id')->nullable()->constrained()->nullOnDelete();

            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->decimal('price', 10)->default(0);
            $table->decimal('vat', 10)->default(0);
            $table->decimal('discount', 10)->default(0);
            $table->decimal('price_with_vat', 10)->default(0);

            $table->boolean('is_paid')->default(false)->index();
            $table->dateTime('paid_at')->nullable();
            $table->string('payment_source')->nullable();

            $table->decimal('refunded_amount', 10)->default(0);
            $table->dateTime('canceled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
