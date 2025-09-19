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
        Schema::create('equipment_tiers', function (Blueprint $table) {
            $table->id();
            $table->integer('tier'); // 0-12
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('quantity_required'); // Quantidade do item necessário
            $table->integer('upgrade_cost_gold')->default(0);
            $table->decimal('success_rate', 5, 2)->default(100.00); // % de chance de sucesso
            $table->enum('failure_penalty', ['maintain', 'downgrade'])->default('maintain');
            $table->timestamps();
            
            // Garantir que cada tier tenha apenas um requisito por item
            $table->unique(['tier', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_tiers');
    }
};
