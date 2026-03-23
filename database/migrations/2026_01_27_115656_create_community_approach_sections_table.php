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
        Schema::create('community_approach_sections', function (Blueprint $table) {
            $table->id();
            $table->text('main_title');
            $table->text('side_title');
            $table->text('sub_title');
            $table->longText('description_1');
            $table->json('points')->nullable(); 
            $table->json('points_2')->nullable(); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_approach_sections');
    }
};
