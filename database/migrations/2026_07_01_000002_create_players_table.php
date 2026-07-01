<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('preferred_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('status')->default('lead');
            $table->string('referral_source')->nullable();
            $table->foreignId('agent_id')->nullable()->constrained('agents');
            $table->foreignId('assigned_admin_id')->nullable()->constrained('agents');
            $table->string('risk_status')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamp('last_played_at')->nullable();
            $table->boolean('compliance_complete')->default(false);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('agent_id');
            $table->index('risk_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
