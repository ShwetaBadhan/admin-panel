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
        Schema::create('about_cares', function (Blueprint $table) {
            $table->id();
            $table->string('sub_title')->default('About Us');
            $table->text('main_title');
            $table->longText('description_1');
            $table->longText('description_2')->nullable();
            $table->string('image')->nullable();
            $table->string('icon_1')->nullable();
            $table->string('feature_1_title')->nullable();
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
        Schema::dropIfExists('about_cares');
    }
};
