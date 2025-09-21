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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->default('default')->after('email');
            $table->text('biography')->nullable()->after('avatar');
            $table->boolean('profile_public')->default(true)->after('biography');
            $table->boolean('is_staff')->default(false)->after('profile_public');
            $table->timestamp('last_seen_at')->nullable()->after('is_staff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'biography', 'profile_public', 'is_staff', 'last_seen_at']);
        });
    }
};
