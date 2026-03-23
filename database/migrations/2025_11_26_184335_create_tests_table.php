<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('tests', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('icon')->nullable(); // stores path like "tests/abc123.jpg"
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('tests');
}
};
