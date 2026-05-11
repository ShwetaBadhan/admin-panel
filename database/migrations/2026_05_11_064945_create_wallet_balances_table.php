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
        Schema::create('wallet_balances', function (Blueprint $table) {
    $table->id();
    $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained('mlm_users')->onDelete('cascade');
    $table->decimal('balance', 15, 2)->default(0);
    $table->decimal('locked_balance', 15, 2)->default(0);
    $table->decimal('total_earned', 15, 2)->default(0);
    $table->decimal('total_withdrawn', 15, 2)->default(0);
    $table->timestamps();
    
    // Unique constraint: one balance entry per user per wallet
    $table->unique(['wallet_id', 'user_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_balances');
    }
};
