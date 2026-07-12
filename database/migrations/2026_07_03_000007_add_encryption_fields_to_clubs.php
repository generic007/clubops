<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->text('encrypted_club_key')->nullable()->after('settings');
            $table->string('encryption_nonce', 40)->nullable()->after('encrypted_club_key');
            // single_club mode = no multi-tenancy UI, auto-created default club
            $table->boolean('single_club')->default(false)->after('encryption_nonce');
        });
    }

    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn(['encrypted_club_key', 'encryption_nonce', 'single_club']);
        });
    }
};
