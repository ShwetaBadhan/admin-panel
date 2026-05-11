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
       Schema::create('wallet_configurations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
    $table->enum('payout_schedule', ['DAILY', 'WEEKLY', 'MONTHLY', 'INSTANT'])->default('WEEKLY');
    $table->string('payout_execution_day')->nullable(); // Monday, Tuesday, etc.
    $table->integer('refund_window_days')->default(30);
    $table->decimal('min_withdraw_amount', 12, 2)->default(500);
    $table->integer('max_payouts_per_batch')->default(500);
    $table->integer('withdraw_cooldown_days')->default(7);
    $table->time('start_window')->nullable(); // 02:30 PM
    $table->time('end_window')->nullable(); // 11:30 PM
    $table->boolean('auto_payout')->default(false);
    $table->decimal('processing_fee_percent', 5, 2)->default(0);
    $table->decimal('processing_fee_fixed', 10, 2)->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_configurations');
    }
};
