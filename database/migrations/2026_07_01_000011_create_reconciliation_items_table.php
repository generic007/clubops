<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reconciliation_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('reconciliation_id')->constrained('reconciliations')->onDelete('cascade');
            $table->foreignId('entry_id')->nullable()->constrained('ledger_entries');
            $table->decimal('amount', 18, 2);
            $table->string('type');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reconciliation_items');
    }
};
