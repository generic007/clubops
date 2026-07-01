<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('entry_number')->unique();
            $table->string('type');
            $table->text('description');
            $table->foreignId('created_by')->constrained('agents');
            $table->nullableMorphs('source');
            $table->string('reference')->nullable();
            $table->date('entry_date');
            $table->foreignId('reversed_entry_id')->nullable()->constrained('ledger_entries');
            $table->boolean('locked')->default(false);
            $table->timestamps();

            $table->index('entry_date');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
