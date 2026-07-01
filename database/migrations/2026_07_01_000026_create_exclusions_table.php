<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exclusions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->string('type');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->constrained('agents');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exclusions');
    }
};
