<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->foreignId('player_id')->constrained('players');
            $table->decimal('buy_in', 18, 2)->nullable();
            $table->decimal('cash_out', 18, 2)->nullable();
            $table->decimal('profit_loss', 18, 2)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
