<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('health_risks', function (Blueprint $table) {
            $table->json('parameter_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('health_risks', function (Blueprint $table) {
            $table->string('parameter_id')->nullable()->change();
        });
    }
};