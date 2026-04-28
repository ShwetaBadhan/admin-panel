<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            
            // Order Details
            $table->timestamp('order_date')->useCurrent();
            $table->decimal('total_amount', 18, 6);
            
            // Enums (stored as strings with validation in Model)
            $table->string('status')->default('PENDING')
                  ->checkIn(['PENDING', 'PAYMENT_FAILED', 'CONFIRMED', 'PACKED', 'SHIPPED', 'DELIVERED', 'CANCELLED', 'RETURN_REQUESTED', 'RETURNED', 'REFUNDED', 'PARTIALLY_REFUNDED']);
            
            $table->string('order_type')->default('SELF')
                  ->checkIn(['SELF', 'SPONSOR']);
            
            $table->string('refund_policy')->default('WITHIN_30_DAYS')
                  ->checkIn(['NONE', 'WITHIN_30_DAYS']);
            
            $table->string('payment_mode')
                  ->checkIn(['ONLINE', 'OFFLINE']);
            
            $table->text('note')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('package_id');
            $table->index('status');
            $table->index('order_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
