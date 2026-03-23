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
        Schema::table('parameters', function (Blueprint $table) {
            // Add slug column only if not exists
            if (!Schema::hasColumn('parameters', 'slug')) {
                $table->string('slug')->unique()->after('title');
            }

            // Change detail_id to JSON
            $table->json('detail_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameters', function (Blueprint $table) {
            $table->dropColumn('slug');

            // Revert detail_id back to unsignedBigInteger
            $table->unsignedBigInteger('detail_id')->nullable()->change();
        });
    }
};
