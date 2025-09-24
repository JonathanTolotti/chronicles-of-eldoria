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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, moderator, staff, etc.
            $table->string('display_name'); // Administrador, Moderador, etc.
            $table->text('description')->nullable(); // Descrição do role
            $table->string('color', 7)->default('#6B7280'); // Cor para exibição (hex)
            $table->string('icon')->nullable(); // Ícone FontAwesome
            $table->integer('level')->default(0); // Nível hierárquico (0 = menor)
            $table->boolean('is_active')->default(true); // Se o role está ativo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
