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
        Schema::table('character_equipment', function (Blueprint $table) {
            // Adicionar campo para controlar se está equipado
            $table->boolean('is_equipped')->default(false)->after('current_tier');
            
            // Adicionar índice para performance
            $table->index(['character_id', 'is_equipped']);
        });
        
        // Remover a constraint única após adicionar o campo
        Schema::table('character_equipment', function (Blueprint $table) {
            $table->dropUnique(['character_id', 'slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('character_equipment', function (Blueprint $table) {
            // Remover o campo is_equipped
            $table->dropColumn('is_equipped');
            
            // Restaurar a constraint única
            $table->unique(['character_id', 'slot']);
            
            // Remover o índice
            $table->dropIndex(['character_id', 'is_equipped']);
        });
    }
};
