<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['potion', 'weapon', 'armor', 'accessory', 'material'])->default('potion');
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'epic', 'legendary'])->default('common');
            $table->string('image_path')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'rarity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
