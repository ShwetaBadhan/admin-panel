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
        Schema::create('aged_services', function (Blueprint $table) {
            $table->id();
            $table->string('sub_title');
            $table->string('main_title');
            
            $table->string('small_card_1_title');
            $table->string('small_card_1_content');

            $table->string('small_card_2_title');
            $table->string('small_card_2_content');

            $table->string('small_card_3_title');
            $table->string('small_card_3_content');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aged_services');
    }
};
