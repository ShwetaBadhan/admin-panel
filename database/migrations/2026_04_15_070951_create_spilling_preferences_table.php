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
        Schema::create('spilling_preferences', function (Blueprint $table) {
            $table->id();
            
            // 🔗 Link to MLM User (NOT Laravel's users table)
            $table->foreignId('mlm_user_id')->unique()->constrained('mlm_users')->cascadeOnDelete();
            
            // 🔹 Preference: Where to place if sponsor's legs are full
            $table->enum('preference', ['LEFT', 'RIGHT', 'HOLDING_TANK'])->default('HOLDING_TANK');
            
            $table->timestamps();
            
            $table->index(['preference'], 'idx_spill_pref');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spilling_preferences');
    }
};
