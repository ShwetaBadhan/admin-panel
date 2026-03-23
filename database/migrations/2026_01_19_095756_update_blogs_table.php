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
        //
        Schema::table('blogs', function (Blueprint $table) {
            // Add benefits column only if not exists
            if (!Schema::hasColumn('blogs', 'benefits')) {
                $table->string('benefits')->unique()->after('status');
            }
            // Add quote column only if not exists
            if (!Schema::hasColumn('blogs', 'quote')) {
                $table->string('quote')->unique()->after('benefits');
            }

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('benefits');

           
        });
         Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('benefits');

           
        });
    }
};
