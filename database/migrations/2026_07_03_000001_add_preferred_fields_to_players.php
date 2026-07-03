<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->string('preferred_game')->nullable()->after('referral_source');
            $table->string('preferred_stakes')->nullable()->after('preferred_game');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['preferred_game', 'preferred_stakes']);
        });
    }
};
