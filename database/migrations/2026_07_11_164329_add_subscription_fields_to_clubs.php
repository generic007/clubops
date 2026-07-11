<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            if (!Schema::hasColumn('clubs', 'stripe_id')) {
                $table->string('stripe_id')->nullable()->after('contact_phone');
            }
            if (!Schema::hasColumn('clubs', 'pm_type')) {
                $table->string('pm_type')->nullable()->after('stripe_id');
            }
            if (!Schema::hasColumn('clubs', 'pm_last_four')) {
                $table->string('pm_last_four', 4)->nullable()->after('pm_type');
            }
            if (!Schema::hasColumn('clubs', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('pm_last_four');
            }
            if (!Schema::hasColumn('clubs', 'subscription_plan_id')) {
                $table->foreignId('subscription_plan_id')->nullable()->after('trial_ends_at')->constrained('subscription_plans')->nullOnDelete();
            }
            if (!Schema::hasColumn('clubs', 'subscription_ends_at')) {
                $table->timestamp('subscription_ends_at')->nullable()->after('subscription_plan_id');
            }
            if (!Schema::hasColumn('clubs', 'subscription_status')) {
                $table->string('subscription_status')->default('inactive')->after('subscription_ends_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropForeign(['subscription_plan_id']);
            $table->dropColumn([
                'stripe_id', 'pm_type', 'pm_last_four',
                'trial_ends_at', 'subscription_plan_id',
                'subscription_ends_at', 'subscription_status',
            ]);
        });
    }
};
