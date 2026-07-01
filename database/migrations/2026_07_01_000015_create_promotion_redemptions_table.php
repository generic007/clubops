<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_redemptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('promotion_id')->constrained('promotions');
            $table->foreignId('player_id')->constrained('players');
            $table->foreignId('ledger_entry_id')->nullable()->constrained('ledger_entries');
            $table->decimal('amount', 18, 2);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('claimed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_redemptions');
    }
};
