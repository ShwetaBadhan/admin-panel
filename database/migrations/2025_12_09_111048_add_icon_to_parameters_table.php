<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('parameters', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('title'); // Stores filename like "cbc.svg"
        });
    }

    public function down()
    {
        Schema::table('parameters', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};