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
        Schema::create('user_commission_settings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('mlm_user_id')->constrained('mlm_users')->onDelete('cascade');
    $table->tinyInteger('commission_percentage'); // 10,12,14,16,18,20
    $table->decimal('amount_per_bottle', 8, 2);   // 100.00 or 200.00 (auto-calculated)
    $table->boolean('is_active')->default(true);
    $table->timestamp('activated_at')->nullable();
    $table->timestamp('deactivated_at')->nullable();
    $table->timestamps();
    
    $table->unique('mlm_user_id'); // One setting per user
    $table->index(['commission_percentage', 'is_active']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_commission_settings');
    }
};
