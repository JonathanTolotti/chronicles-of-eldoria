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
        Schema::table('items', function (Blueprint $table) {
            // Modificar o enum para incluir 'cosmetic'
            $table->enum('type', ['potion', 'weapon', 'armor', 'accessory', 'material', 'cosmetic'])->default('potion')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Reverter para o enum original
            $table->enum('type', ['potion', 'weapon', 'armor', 'accessory', 'material'])->default('potion')->change();
        });
    }
};
