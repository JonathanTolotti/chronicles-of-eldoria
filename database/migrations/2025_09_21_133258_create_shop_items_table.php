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
        Schema::create('shop_items', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            
            // Relacionamento com item ou equipment
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('equipment_id')->nullable();
            
            // Preços em duas moedas
            $table->integer('gold_price')->default(0);
            $table->integer('coin_price')->default(0);
            
            // Disponibilidade e limites
            $table->boolean('is_available')->default(true);
            $table->integer('stock_quantity')->nullable(); // null = ilimitado
            $table->integer('daily_limit')->nullable(); // limite por dia por personagem
            
            // Requisitos de nível
            $table->integer('min_level')->default(1);
            $table->integer('max_level')->nullable();
            
            // Configurações especiais
            $table->boolean('is_featured')->default(false); // item em destaque
            $table->boolean('is_limited_time')->default(false); // tempo limitado
            $table->timestamp('available_until')->nullable();
            $table->integer('discount_percentage')->default(0); // desconto em %
            
            $table->timestamps();
            
            // Índices
            $table->index(['item_id', 'equipment_id']);
            $table->index(['is_available', 'is_featured']);
            $table->index(['min_level', 'max_level']);
            $table->index(['gold_price', 'coin_price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_items');
    }
};
