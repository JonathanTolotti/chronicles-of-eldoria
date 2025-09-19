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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['weapon', 'armor', 'helmet', 'shield', 'boots', 'pants']);
            $table->string('image')->nullable();
            
            // Stats base do equipamento
            $table->integer('strength_bonus')->default(0);
            $table->integer('dexterity_bonus')->default(0);
            $table->integer('constitution_bonus')->default(0);
            $table->integer('intelligence_bonus')->default(0);
            $table->integer('luck_bonus')->default(0);
            $table->integer('hp_bonus')->default(0);
            $table->integer('mp_bonus')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
