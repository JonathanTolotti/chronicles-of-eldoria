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
        Schema::create('item_effects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->enum('effect_type', [
                'heal_hp', 
                'restore_stamina', 
                'restore_mp', 
                'buff_strength', 
                'buff_dexterity', 
                'buff_constitution', 
                'buff_intelligence', 
                'buff_luck'
            ]);
            $table->integer('effect_value');
            $table->integer('effect_duration')->nullable(); // em segundos para buffs
            $table->timestamps();
            
            $table->index(['item_id', 'effect_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_effects');
    }
};
