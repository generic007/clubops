<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_platform_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->string('platform');
            $table->string('username');
            $table->string('user_id')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();

            $table->unique(['platform', 'username']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_platform_accounts');
    }
};
