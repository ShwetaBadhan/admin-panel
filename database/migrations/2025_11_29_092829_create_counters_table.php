<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->string('title1')->default('Expert Doctors');
            $table->integer('count1')->default(90);
            $table->string('title2')->default('Different Services');
            $table->integer('count2')->default(26);
            $table->string('title3')->default('Happy Patients');
            $table->integer('count3')->default(35);
            $table->string('title4')->default('Awards Win');
            $table->integer('count4')->default(10);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('counters');
    }
};
