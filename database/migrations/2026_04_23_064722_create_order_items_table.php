<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // ✅ Foreign Key to orders table
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            
            // ✅ Foreign Key to products table (NOT product_variants)
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price', 18, 6); // Price at time of order
            
            // Optional: If you want to track which variant/options were selected
            // $table->json('options')->nullable(); // e.g., {"size": "L", "color": "red"}
            
            $table->string('status')->default('PENDING');
            // Enum values: PENDING, SHIPPED, DELIVERED, CANCELLED (validate in Model)
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['order_id', 'product_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
