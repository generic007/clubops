<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->boolean('can_login')->default(false)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['password', 'remember_token', 'last_login_at', 'can_login']);
        });
    }
};
