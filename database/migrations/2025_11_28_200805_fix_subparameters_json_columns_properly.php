<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ──────────────────────────────────────────────────────────────
        // 1. Add the new JSON columns WITHOUT using ->after() if columns may not exist
        // ──────────────────────────────────────────────────────────────
        Schema::table('subparameters', function (Blueprint $table) {
            // Only add if the temporary column does not already exist
            if (! Schema::hasColumn('subparameters', 'parameter_id_json')) {
                $table->json('parameter_id_json')->nullable();
            }
            if (! Schema::hasColumn('subparameters', 'test_ids_json')) {
                $table->json('test_ids_json')->nullable();
            }
        });

        // ──────────────────────────────────────────────────────────────
        // 2. Migrate data from old columns → new JSON columns
        // ──────────────────────────────────────────────────────────────

        if (Schema::hasColumn('subparameters', 'parameter_id')) {
            DB::statement("UPDATE subparameters 
                           SET parameter_id_json = JSON_ARRAY(parameter_id) 
                           WHERE parameter_id IS NOT NULL");
        }

        if (Schema::hasColumn('subparameters', 'test_ids')) {
            DB::statement("UPDATE subparameters 
                           SET test_ids_json = CASE 
                               WHEN test_ids IS NULL OR test_ids = '' THEN JSON_ARRAY()
                               ELSE JSON_ARRAY(test_ids)
                           END 
                           WHERE test_ids IS NOT NULL");
        }

        // If old column was empty or null → make sure we have at least []
        DB::statement("UPDATE subparameters SET test_ids_json = JSON_ARRAY() WHERE test_ids_json IS NULL");
        DB::statement("UPDATE subparameters SET parameter_id_json = JSON_ARRAY() WHERE parameter_id_json IS NULL");

        // ──────────────────────────────────────────────────────────────
        // 3. Drop old columns (only if they exist)
        // ──────────────────────────────────────────────────────────────
        Schema::table('subparameters', function (Blueprint $table) {
            if (Schema::hasColumn('subparameters', 'parameter_id')) {
                $table->dropColumn('parameter_id');
            }
            if (Schema::hasColumn('subparameters', 'test_ids')) {
                $table->dropColumn('test_ids');
            }
        });

        // ──────────────────────────────────────────────────────────────
        // 4. Rename temporary columns to final names
        // ──────────────────────────────────────────────────────────────
        Schema::table('subparameters', function (Blueprint $table) {
            $table->renameColumn('parameter_id_json', 'parameter_id');
            $table->renameColumn('test_ids_json', 'test_ids');
        });
    }

    public function down()
    {
        // We will create temporary old-style columns, copy data back, then swap
        Schema::table('subparameters', function (Blueprint $table) {
            $table->unsignedBigInteger('parameter_id_old')->nullable();
            $table->json('test_ids_old')->nullable(); // or text/string if it was before
        });

        // Copy back
        DB::statement("UPDATE subparameters 
                       SET parameter_id_old = JSON_UNQUOTE(JSON_EXTRACT(parameter_id, '$[0]'))");

        DB::statement("UPDATE subparameters 
                       SET test_ids_old = JSON_ARRAY()"); // or convert how you need

        // Drop current JSON columns and rename old ones back
        Schema::table('subparameters', function (Blueprint $table) {
            $table->dropColumn(['parameter_id', 'test_ids']);
            $table->renameColumn('parameter_id_old', 'parameter_id');
            $table->renameColumn('test_ids_old', 'test_ids');
        });
    }
};