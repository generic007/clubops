<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ticket_number')->unique();
            $table->foreignId('player_id')->nullable()->constrained('players');
            $table->foreignId('assigned_to')->nullable()->constrained('agents');
            $table->string('subject');
            $table->text('description');
            $table->string('type');
            $table->string('priority')->default('normal');
            $table->string('status')->default('open');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
