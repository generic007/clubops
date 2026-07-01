<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('agents');
            $table->text('note');
            $table->string('category')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_notes');
    }
};
