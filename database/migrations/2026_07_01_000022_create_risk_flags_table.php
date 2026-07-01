<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_flags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('raised_by')->constrained('agents');
            $table->foreignId('resolved_by')->nullable()->constrained('agents');
            $table->string('type');
            $table->text('description');
            $table->string('severity')->default('medium');
            $table->string('status')->default('open');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_flags');
    }
};
