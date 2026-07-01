<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('import_id')->constrained('imports')->onDelete('cascade');
            $table->integer('row_number');
            $table->json('raw_data');
            $table->json('mapped_data')->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_rows');
    }
};
