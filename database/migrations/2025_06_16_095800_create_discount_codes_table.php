<?php

use App\Enums\DiscountCodeType;
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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->string('code')->unique();
            $table->enum('type', array_column(DiscountCodeType::cases(), 'value'))->default(DiscountCodeType::PERCENT->value);
            $table->decimal('value');
            $table->integer('usage_limit')->nullable();
            $table->integer('used')->default(0);
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
