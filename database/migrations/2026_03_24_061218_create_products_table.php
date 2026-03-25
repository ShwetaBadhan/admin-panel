<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('discount_percentage')->default(0);
            $table->string('size')->nullable(); // e.g., 800 ML, 400ml
            $table->string('brand')->nullable();
            $table->string('type')->nullable(); // Veg Box, Organic
            $table->boolean('is_organic')->default(false);
            $table->integer('stock')->default(0);
            $table->boolean('in_stock')->default(true);
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('review_count')->default(0);
            $table->json('images')->nullable(); // Multiple images
            $table->json('additional_info')->nullable(); // Additional information table
            $table->boolean('status')->default(1); // 1=Active, 0=Inactive
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};