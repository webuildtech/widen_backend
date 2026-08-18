<?php

use App\Enums\GameParticipantStatus;
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
        Schema::create('game_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_id')->constrained()->cascadeOnDelete();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            // Whoever paid for this spot - set when a user brings guests along.
            $table->foreignId('added_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('game_level_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();

            $table->string('email');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->enum('status', array_column(GameParticipantStatus::cases(), 'value'))
                ->default(GameParticipantStatus::PENDING->value);

            $table->decimal('price', 10)->default(0);
            $table->decimal('vat', 10)->default(0);
            $table->decimal('discount', 10)->default(0);
            $table->decimal('price_with_vat', 10)->default(0);
            $table->decimal('refunded_amount', 10)->default(0);

            $table->dateTime('joined_at')->nullable();
            $table->dateTime('canceled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['game_id', 'status']);
            $table->index(['game_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_participants');
    }
};
