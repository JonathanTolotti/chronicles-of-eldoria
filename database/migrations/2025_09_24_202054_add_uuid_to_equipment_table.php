<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Adicionar UUID para equipamentos existentes
        DB::table('equipment')->get()->each(function ($equipment) {
            DB::table('equipment')
                ->where('id', $equipment->id)
                ->update(['uuid' => Str::uuid()]);
        });

        // Agora tornar o campo único e não nulo
        Schema::table('equipment', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
