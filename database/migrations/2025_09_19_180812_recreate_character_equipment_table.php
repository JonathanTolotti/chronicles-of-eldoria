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
        // Dropar a tabela atual
        Schema::dropIfExists('character_equipment');
        
        // Recriar a tabela com a estrutura correta
        Schema::create('character_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->onDelete('cascade');
            $table->enum('slot', ['helmet', 'armor', 'weapon', 'shield', 'boots', 'pants']);
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->integer('current_tier')->default(0);
            $table->boolean('is_equipped')->default(false); // Campo para controlar se está equipado
            $table->timestamps();
            
            // Índices para performance
            $table->index(['character_id', 'is_equipped']);
            $table->index(['character_id', 'slot', 'is_equipped']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dropar a tabela
        Schema::dropIfExists('character_equipment');
        
        // Recriar a tabela original
        Schema::create('character_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->onDelete('cascade');
            $table->enum('slot', ['helmet', 'armor', 'weapon', 'shield', 'boots', 'pants']);
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->integer('current_tier')->default(0);
            $table->timestamps();
            
            // Constraint única original
            $table->unique(['character_id', 'slot']);
        });
    }
};
