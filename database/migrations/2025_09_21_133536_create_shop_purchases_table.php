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
        Schema::create('shop_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            
            // Relacionamentos
            $table->unsignedBigInteger('character_id');
            $table->unsignedBigInteger('shop_item_id');
            
            // Detalhes da compra
            $table->integer('quantity')->default(1);
            $table->integer('gold_paid')->default(0);
            $table->integer('coin_paid')->default(0);
            $table->integer('discount_applied')->default(0); // desconto aplicado em %
            
            // Status da compra
            $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('completed');
            $table->text('notes')->nullable(); // notas sobre a compra
            
            $table->timestamps();
            
            // Índices
            $table->index(['character_id', 'created_at']);
            $table->index(['shop_item_id']);
            $table->index(['status']);
            
            // Foreign keys
            $table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade');
            $table->foreign('shop_item_id')->references('id')->on('shop_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_purchases');
    }
};
