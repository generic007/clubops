<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_tag', function (Blueprint $table) {
            $table->foreignId('player_id')->constrained('players');
            $table->foreignId('tag_id')->constrained('tags');

            $table->primary(['player_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_tag');
    }
};
