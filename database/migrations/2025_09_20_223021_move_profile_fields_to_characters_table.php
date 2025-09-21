<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adicionar campos de perfil na tabela characters
        Schema::table('characters', function (Blueprint $table) {
            $table->string('avatar')->default('default')->after('class');
            $table->text('biography')->nullable()->after('avatar');
            $table->boolean('profile_public')->default(true)->after('biography');
        });

        // Migrar dados existentes de users para characters
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('characters')
                ->where('user_id', $user->id)
                ->update([
                    'avatar' => $user->avatar ?? 'default',
                    'biography' => $user->biography,
                    'profile_public' => $user->profile_public ?? true,
                ]);
        }

        // Remover campos da tabela users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'biography', 'profile_public']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Adicionar campos de volta na tabela users
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->default('default')->after('email');
            $table->text('biography')->nullable()->after('avatar');
            $table->boolean('profile_public')->default(true)->after('biography');
        });

        // Migrar dados de volta de characters para users
        $characters = DB::table('characters')->get();
        foreach ($characters as $character) {
            DB::table('users')
                ->where('id', $character->user_id)
                ->update([
                    'avatar' => $character->avatar ?? 'default',
                    'biography' => $character->biography,
                    'profile_public' => $character->profile_public ?? true,
                ]);
        }

        // Remover campos da tabela characters
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'biography', 'profile_public']);
        });
    }
};
