<?php

use App\Enums\GameStatus;
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
        Schema::create('games', function (Blueprint $table) {
            $table->id();

            $table->uuid()->unique();

            $table->foreignId('court_type_id')->constrained();
            $table->foreignId('court_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained()->nullOnDelete();

            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->unsignedTinyInteger('capacity');

            // Price is per person; the total is only ever a display value (price_with_vat * capacity).
            $table->decimal('price', 10)->default(0);
            $table->decimal('vat', 10)->default(0);
            $table->decimal('price_with_vat', 10)->default(0);

            $table->enum('status', array_column(GameStatus::cases(), 'value'))
                ->default(GameStatus::PUBLISHED->value);
            $table->dateTime('canceled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->json('title')->nullable();
            $table->json('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'start_time']);
            $table->index(['court_id', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
