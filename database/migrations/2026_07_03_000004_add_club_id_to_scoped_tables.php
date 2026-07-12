<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agents — belongs to a club
        Schema::table('agents', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
            // Change unique(email) to unique per club
            $table->dropUnique(['email']);
        });
        // Add unique per club — separate call because SQLite
        Schema::table('agents', function (Blueprint $table) {
            $table->unique(['club_id', 'email']);
        });

        // Players
        Schema::table('players', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });

        // Tags — name unique per club
        Schema::table('tags', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
            $table->dropUnique(['name']);
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->unique(['club_id', 'name']);
        });

        // Ledger accounts — code unique per club
        Schema::table('ledger_accounts', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
            $table->dropUnique(['code']);
        });
        Schema::table('ledger_accounts', function (Blueprint $table) {
            $table->unique(['club_id', 'code']);
        });

        // Ledger entries — entry_number unique per club
        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
            $table->dropUnique(['entry_number']);
        });
        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->unique(['club_id', 'entry_number']);
        });

        // Reconciliations — date unique per club
        Schema::table('reconciliations', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
            $table->dropUnique(['reconciliation_date']);
        });
        Schema::table('reconciliations', function (Blueprint $table) {
            $table->unique(['club_id', 'reconciliation_date']);
        });

        // Support tickets — ticket_number unique per club
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
            $table->dropUnique(['ticket_number']);
        });
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->unique(['club_id', 'ticket_number']);
        });

        // Player platform accounts — platform+username unique per club
        Schema::table('player_platform_accounts', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
            $table->dropUnique(['platform', 'username']);
        });
        Schema::table('player_platform_accounts', function (Blueprint $table) {
            $table->unique(['club_id', 'platform', 'username']);
        });

        // Promotions
        Schema::table('promotions', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });

        // Promotion redemptions
        Schema::table('promotion_redemptions', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });

        // Games
        Schema::table('games', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });

        // Game sessions
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });

        // Imports
        Schema::table('imports', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });

        // Import rows
        Schema::table('import_rows', function (Blueprint $table) {
            $table->after('import_id', function ($table) {
                $table->foreignId('club_id')->nullable()->constrained('clubs');
            });
        });

        // Risk flags
        Schema::table('risk_flags', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });

        // Communication templates
        Schema::table('communication_templates', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });

        // Message drafts
        Schema::table('message_drafts', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });

        // Compliance profiles
        Schema::table('compliance_profiles', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });

        // Exclusions
        Schema::table('exclusions', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });

        // Audit logs
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->foreignId('club_id')->nullable()->after('id')->constrained('clubs');
        });
    }

    public function down(): void
    {
        // Reverse is too complex for SQLite — skip
    }
};
