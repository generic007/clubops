<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('reconciliation_date')->unique();
            $table->string('status')->default('draft');
            $table->decimal('platform_total', 18, 2);
            $table->decimal('ledger_total', 18, 2);
            $table->decimal('variance', 18, 2);
            $table->foreignId('created_by')->constrained('agents');
            $table->foreignId('locked_by')->nullable()->constrained('agents');
            $table->timestamp('locked_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reconciliations');
    }
};
