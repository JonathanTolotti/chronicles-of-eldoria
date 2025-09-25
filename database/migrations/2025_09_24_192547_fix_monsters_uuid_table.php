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
        Schema::table('monsters', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Verificar se a coluna uuid existe e se tem valores vazios
        $hasEmptyUuids = DB::table('monsters')->whereNull('uuid')->orWhere('uuid', '')->exists();
        
        if ($hasEmptyUuids) {
            // Atualizar UUIDs vazios
            $monsters = DB::table('monsters')->whereNull('uuid')->orWhere('uuid', '')->get();
            foreach ($monsters as $monster) {
                DB::table('monsters')
                    ->where('id', $monster->id)
                    ->update(['uuid' => Str::uuid()]);
            }
        }

        // Tornar a coluna única e não nula
        Schema::table('monsters', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monsters', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->change();
        });
    }
};