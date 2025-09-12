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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('class', ['warrior', 'mage', 'archer', 'rogue'])->default('warrior');
            $table->integer('level')->default(1);
            $table->bigInteger('experience')->default(0);
            $table->bigInteger('experience_to_next_level')->default(100);
            
            // Stats
            $table->integer('strength')->default(10);
            $table->integer('dexterity')->default(10);
            $table->integer('constitution')->default(10);
            $table->integer('intelligence')->default(10);
            $table->integer('luck')->default(10);
            
            // Calculated stats
            $table->integer('max_hp')->default(100);
            $table->integer('current_hp')->default(100);
            $table->integer('max_mp')->default(50);
            $table->integer('current_mp')->default(50);
            $table->integer('power')->default(0);
            
            // Resources
            $table->bigInteger('gold')->default(100);
            $table->integer('stat_points')->default(0);
            
            // Training
            $table->string('training_stat')->nullable();
            $table->timestamp('training_ends_at')->nullable();
            $table->integer('training_points')->default(0);
            
            $table->timestamps();
            
            $table->index(['user_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
