<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('refunded_amount', 10)->default(0)->after('paid_amount_from_balance');
            $table->decimal('refunded_amount_to_balance', 10)->default(0)->after('refunded_amount');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['refunded_amount', 'refunded_amount_to_balance']);
        });
    }
};
