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
        Schema::create('values', function (Blueprint $table) {
           $table->id();
            $table->string('sub_title');
            $table->string('main_title');
          
            // Small Feature Cards (4 cards)
            $table->string('small_card_1_image')->nullable();
            $table->string('small_card_1_title');
            $table->string('small_card_1_main_title');

            $table->string('small_card_2_image')->nullable();
            $table->string('small_card_2_title');
            $table->string('small_card_2_main_title');

            $table->string('small_card_3_image')->nullable();
            $table->string('small_card_3_title');
            $table->string('small_card_3_main_title');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('values');
    }
};
