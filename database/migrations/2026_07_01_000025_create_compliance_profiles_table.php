<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->date('date_of_birth')->nullable();
            $table->string('location')->nullable();
            $table->string('id_verification_status')->default('not_verified');
            $table->timestamp('id_verified_at')->nullable();
            $table->text('compliance_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_profiles');
    }
};
