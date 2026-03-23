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
        Schema::create('job_career_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_careers')->onDelete('cascade');
            $table->string('job_title'); 
            $table->string('fullname');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->text('details')->nullable();
            $table->string('resume')->nullable();

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_career_applications');
    }
};
