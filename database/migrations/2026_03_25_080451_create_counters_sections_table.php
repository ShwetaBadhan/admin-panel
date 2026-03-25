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
        Schema::create('counters_sections', function (Blueprint $table) {
            $table->id();
            $table->json('counters'); // Array of counters
            $table->string('background_image')->nullable();
            $table->string('background_color')->default('#2d7a3e'); // Green color
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counters_sections');
    }
};
