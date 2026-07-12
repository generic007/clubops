<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug', 60)->unique();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('description')->nullable();
            $table->string('timezone')->default('America/Los_Angeles');
            $table->string('currency')->default('USD');
            $table->boolean('active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
