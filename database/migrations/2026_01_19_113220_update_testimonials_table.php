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
        //
         Schema::table('testimonials', function (Blueprint $table) {
            // Add benefits column only if not exists
            if (!Schema::hasColumn('testimonials', 'quote')) {
                $table->string('quote')->unique()->after('status');
            }
            // Add quote column only if not exists
            if (!Schema::hasColumn('testimonials', 'photo')) {
                $table->string('photo')->unique()->after('quote');
            }

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('quote');

           
        });
         Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('photo');

           
        });
    }
};
