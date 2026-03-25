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
        Schema::create('services_sections', function (Blueprint $table) {
            $table->id();
            
            // Header Text
            $table->string('subtitle')->default('OUR BEST SERVICES');
            $table->string('main_heading')->default('We Providing High Quality');
            
            // Dynamic Left Side Pointers (Stored as JSON)
            $table->json('service_items')->nullable(); 
            
            // Center Icon & Active Content
            $table->string('icon')->nullable();
            $table->string('active_item_title')->nullable();
            $table->text('active_item_description')->nullable();
            $table->string('read_more_link')->nullable();
            
            // Right Side Image
            $table->string('image')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_sections');
    }
};