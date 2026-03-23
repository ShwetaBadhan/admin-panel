<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('package_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->longText('answer');

            // This FAQ can belong to either a Parameter OR a Health Package (Subparameter)
            $table->unsignedBigInteger('parameter_id')->nullable()->index();
            $table->unsignedBigInteger('subparameter_id')->nullable()->index();

            $table->foreign('parameter_id')
                  ->references('id')->on('parameters')
                  ->onDelete('cascade');

            $table->foreign('subparameter_id')
                  ->references('id')->on('subparameters')
                  ->onDelete('cascade');

            // Optional: make sure only one of them is filled
            $table->unique(['parameter_id', 'subparameter_id']);

            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_faqs');
    }
};