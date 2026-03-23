<?php
// database/migrations/xxxx_xx_xx_create_job_careers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');                          
            $table->string('slug')->unique();                 
            $table->string('department')->nullable();         
            $table->string('type');                          
            $table->string('location');                      
            $table->text('description')->nullable();         
            $table->string('experience');                    
            $table->string('qualification');                 
            $table->string('salary_range')->nullable();      
            $table->boolean('is_featured')->default(false);   
            $table->boolean('is_active')->default(true);     
            $table->integer('sort_order')->default(0);        
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_careers');
    }
};