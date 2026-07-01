<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type');
            $table->text('description')->nullable();
            $table->decimal('value', 18, 2);
            $table->decimal('cap', 18, 2)->nullable();
            $table->decimal('total_liability', 18, 2)->default(0);
            $table->decimal('claimed_liability', 18, 2)->default(0);
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->boolean('active')->default(true);
            $table->text('terms')->nullable();
            $table->json('eligibility_rules')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
