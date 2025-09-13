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
        Schema::table('characters', function (Blueprint $table) {
            $table->renameColumn('current_mp', 'current_stamina');
            $table->renameColumn('max_mp', 'max_stamina');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->renameColumn('current_stamina', 'current_mp');
            $table->renameColumn('max_stamina', 'max_mp');
        });
    }
};