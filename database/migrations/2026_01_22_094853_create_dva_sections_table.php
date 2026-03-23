<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dva_sections', function (Blueprint $table) {
            $table->id();
            $table->text('about_content');
            $table->text('service_title');
            $table->text('service_about');
            $table->text('eligibility');
            $table->json('points')->nullable();
            $table->string('image_1')->nullable();
            $table->string('image_2')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dva_sections');
    }
};
