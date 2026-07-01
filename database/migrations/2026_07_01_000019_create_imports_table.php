<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('filename');
            $table->string('status')->default('pending');
            $table->integer('total_rows')->default(0);
            $table->integer('accepted_rows')->default(0);
            $table->integer('skipped_rows')->default(0);
            $table->integer('flagged_rows')->default(0);
            $table->text('error_log')->nullable();
            $table->foreignId('created_by')->constrained('agents');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
