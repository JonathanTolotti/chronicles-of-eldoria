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
        Schema::create('character_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->onDelete('cascade');
            $table->enum('slot', ['helmet', 'armor', 'weapon', 'shield', 'boots', 'pants']);
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->integer('current_tier')->default(0);
            $table->timestamps();
            
            // Garantir que cada personagem tenha apenas um equipamento por slot
            $table->unique(['character_id', 'slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_equipment');
    }
};
