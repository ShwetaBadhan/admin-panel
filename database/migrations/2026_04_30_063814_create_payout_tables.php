<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Payout configuration (global settings)
        Schema::create('payout_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('products_for_payout')->default(40); // 40 products threshold
            $table->integer('cc_per_product')->default(20); // 20 CC per product
            $table->decimal('cc_to_currency_rate', 10, 2)->default(60.00); // 1 CC = ₹60
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // User payout balances
        Schema::create('payout_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mlm_user_id')->constrained('mlm_users')->onDelete('cascade');
            
            // CC tracking
            $table->decimal('personal_cc', 12, 2)->default(0); // User's own purchases
            $table->decimal('left_team_cc', 12, 2)->default(0); // Left leg total
            $table->decimal('right_team_cc', 12, 2)->default(0); // Right leg total
            
            // Payout tracking
            $table->decimal('available_balance', 12, 2)->default(0); // Can withdraw
            $table->decimal('locked_balance', 12, 2)->default(0); // Calculated but can't withdraw
            $table->decimal('total_earned', 12, 2)->default(0); // Lifetime earnings
            $table->decimal('total_withdrawn', 12, 2)->default(0);
            
            // Eligibility
            $table->boolean('is_payout_eligible')->default(false); // Has >= 40 products
            $table->timestamp('last_payout_at')->nullable();
            
            $table->timestamps();
            
            $table->unique('mlm_user_id');
            $table->index(['is_payout_eligible', 'available_balance']);
        });

        // Payout transactions (audit trail)
        Schema::create('payout_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mlm_user_id')->constrained('mlm_users')->onDelete('cascade');
            $table->string('type'); // 'personal_purchase', 'team_match', 'override', 'withdrawal'
            $table->decimal('cc_amount', 12, 2);
            $table->decimal('currency_amount', 12, 2);
            $table->string('status'); // 'pending', 'credited', 'locked', 'withdrawn'
            $table->text('description')->nullable();
            $table->json('meta')->nullable(); // {order_id, matched_user_id, etc.}
            $table->timestamps();
            
            $table->index(['mlm_user_id', 'type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_transactions');
        Schema::dropIfExists('payout_balances');
        Schema::dropIfExists('payout_configs');
    }
};