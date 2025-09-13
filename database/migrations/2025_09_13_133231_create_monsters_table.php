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
        Schema::create('monsters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('level')->default(1);
            $table->integer('max_hp');
            $table->integer('current_hp');
            $table->integer('attack_power');
            $table->integer('defense');
            $table->integer('speed');
            $table->bigInteger('gold_reward')->default(0);
            $table->bigInteger('exp_reward')->default(0);
            $table->string('image')->nullable(); // Para ícone/emoji do monstro
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monsters');
    }
};
