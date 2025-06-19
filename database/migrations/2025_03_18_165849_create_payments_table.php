<?php

use App\Enums\PaymentStatus;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->morphs('owner');

            $table->nullableMorphs('paymentable');

            $table->string('transaction_id')->nullable();
            $table->enum('status', array_column(PaymentStatus::cases(), 'value'))
                ->default(PaymentStatus::PENDING->value);

            $table->boolean('renew')->default(false);

            $table->decimal('price', 10)->default(0);
            $table->decimal('vat', 10)->default(0);
            $table->decimal('price_with_vat', 10)->default(0);
            $table->decimal('discount', 10)->default(0);

            $table->decimal('paid_amount', 10)->default(0);
            $table->decimal('paid_amount_from_balance', 10)->default(0);

            $table->dateTime('paid_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
