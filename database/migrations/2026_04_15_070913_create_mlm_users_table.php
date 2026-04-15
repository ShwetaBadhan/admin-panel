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
        Schema::create('mlm_users', function (Blueprint $table) {
            $table->id();
            
            // 🔹 Identity (No relation to Laravel's users table)
            $table->string('user_name')->unique();
            $table->string('track_id', 30)->unique();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            
            // 🔹 Status Flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_defaulter')->default(false);
            $table->boolean('is_payout_active')->default(false);
            
            // 🔹 Verification
            $table->string('verification_token')->nullable();
            $table->timestamp('verification_expires')->nullable();
            
            // 🔹 Membership Type
            $table->enum('membership_type', ['CUSTOMER', 'PREFERRED_CUSTOMER', 'DIRECT_SELLER'])->default('CUSTOMER');
            $table->enum('desired_membership_type', ['CUSTOMER', 'PREFERRED_CUSTOMER', 'DIRECT_SELLER'])->nullable();
            
            // 🔹 Binary MLM: Self-referential Sponsor (within mlm_users table)
            $table->foreignId('sponsor_id')->nullable()->constrained('mlm_users')->nullOnDelete();
            $table->enum('position_in_sponsor_leg', ['left', 'right', 'none'])->default('none');
            
            // 🔹 Package Reference
            $table->foreignId('current_package_id')->nullable()->constrained('packages')->nullOnDelete();
            
            $table->timestamps();
            
            // 🔹 Indexes for MLM queries
            $table->index(['sponsor_id', 'position_in_sponsor_leg'], 'idx_mlm_sponsor_pos');
            $table->index(['membership_type', 'is_active'], 'idx_mlm_membership');
            $table->index('track_id', 'idx_mlm_track');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_users');
    }
};
