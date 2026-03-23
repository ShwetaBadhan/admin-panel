<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accreditation_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title1')->nullable();
            $table->string('icon1')->nullable(); 
            $table->string('title2')->nullable();
            $table->string('icon2')->nullable();
            $table->string('title3')->nullable();
            $table->string('icon3')->nullable();
            $table->string('title4')->nullable();
            $table->string('icon4')->nullable();
            $table->timestamps();
        });

       
    }

    public function down(): void
    {
        Schema::dropIfExists('accreditation_sections');
    }
};