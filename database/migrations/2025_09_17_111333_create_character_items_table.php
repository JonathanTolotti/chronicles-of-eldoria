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
        Schema::create('character_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->boolean('is_equipped')->default(false);
            $table->enum('equipment_slot', [
                'weapon', 
                'helmet', 
                'armor', 
                'boots', 
                'accessory1', 
                'accessory2'
            ])->nullable(); // slot do equipamento quando is_equipped = true
            $table->integer('slot_position')->nullable(); // 1-4 para hotkeys F1-F4
            $table->timestamps();
            
            $table->index(['character_id', 'slot_position']);
            $table->index(['character_id', 'is_equipped']);
            $table->index(['character_id', 'equipment_slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_items');
    }
};
