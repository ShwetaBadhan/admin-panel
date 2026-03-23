<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {

            // Drop unique indexes first
            if (Schema::hasColumn('blogs', 'benefits')) {
                $table->dropUnique('blogs_benefits_unique');
            }

            if (Schema::hasColumn('blogs', 'quote')) {
                $table->dropUnique('blogs_quote_unique');
            }

            // Change column types
            $table->text('benefits')->nullable()->change();
            $table->string('quote')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {

            // Revert to original state
            $table->string('benefits')->unique()->change();
            $table->string('quote')->unique()->change();
        });
    }
};
