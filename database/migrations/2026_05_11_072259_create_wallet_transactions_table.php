<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('mlm_users')->onDelete('cascade');
            
            // Transaction Details
            $table->enum('type', ['credit', 'debit'])->comment('credit = income, debit = withdrawal/fee');
            $table->decimal('amount', 15, 2)->comment('Transaction amount');
            $table->decimal('balance_after', 15, 2)->comment('Wallet balance after this transaction');
            
            // Reference Info (for tracking source)
            $table->string('reference_type')->nullable()->comment('bonus, commission, matching_income, withdrawal, platform_fee, tds, adjustment');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('ID of reference model (order_id, withdrawal_id, etc.)');
            
            // Status
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
            
            // Description & Metadata
            $table->string('description')->nullable();
            $table->json('meta')->nullable()->comment('Additional data like order details, fee breakdown, etc.');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};