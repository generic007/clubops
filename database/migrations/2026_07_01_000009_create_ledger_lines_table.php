<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('entry_id')->constrained('ledger_entries');
            $table->foreignId('account_id')->constrained('ledger_accounts');
            $table->foreignId('player_id')->nullable()->constrained('players');
            $table->decimal('debit', 18, 2)->default(0);
            $table->decimal('credit', 18, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_lines');
    }
};
