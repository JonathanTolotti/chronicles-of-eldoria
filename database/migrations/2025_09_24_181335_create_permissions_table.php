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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin.users.create, moderator.users.view, etc.
            $table->string('display_name'); // Criar Usuários, Visualizar Usuários, etc.
            $table->text('description')->nullable(); // Descrição da permissão
            $table->string('category')->default('general'); // Categoria: users, characters, battles, etc.
            $table->boolean('is_active')->default(true); // Se a permissão está ativa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
