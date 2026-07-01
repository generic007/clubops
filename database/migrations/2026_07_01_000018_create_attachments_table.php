<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('attachable');
            $table->foreignId('uploaded_by')->constrained('agents');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->integer('size_bytes');
            $table->string('disk')->default('private');
            $table->string('path');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['attachable_type', 'attachable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
