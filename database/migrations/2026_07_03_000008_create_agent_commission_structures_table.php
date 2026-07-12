<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_commission_structures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('club_id')->constrained();
            $table->foreignId('agent_id')->constrained();
            $table->string('type'); // rakeback_percentage, flat_fee_per_player, volume_tiered
            $table->decimal('rate', 8, 4)->default(0); // e.g. 0.25 = 25%
            $table->json('tiers')->nullable(); // for volume-based: [{min:0, rate:0.1}, {min:10000, rate:0.2}]
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('password');
            $table->decimal('commission_balance', 18, 2)->default(0)->after('last_login_at');
            $table->timestamp('last_settled_at')->nullable()->after('commission_balance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_commission_structures');
        Schema::table('agents', function ($t) {
            $t->dropColumn(['last_login_at', 'commission_balance', 'last_settled_at']);
        });
    }
};
