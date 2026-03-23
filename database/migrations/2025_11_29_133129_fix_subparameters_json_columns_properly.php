<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Step 1: Add new JSON columns
        Schema::table('subparameters', function (Blueprint $table) {
            $table->json('parameter_id_json')->nullable()->after('parameter_id');
            $table->json('test_ids_json')->nullable()->after('test_ids');
        });

        // Step 2: Copy data from old columns to new JSON columns
        DB::statement("UPDATE subparameters SET parameter_id_json = JSON_ARRAY(parameter_id) WHERE parameter_id IS NOT NULL");
        DB::statement("UPDATE subparameters SET test_ids_json = JSON_ARRAY() WHERE test_ids_json IS NULL");

        // Step 3: Drop old columns
        Schema::table('subparameters', function (Blueprint $table) {
            $table->dropColumn(['parameter_id', 'test_ids']);
        });

        // Step 4: Rename new columns to correct names
        Schema::table('subparameters', function (Blueprint $table) {
            $table->renameColumn('parameter_id_json', 'parameter_id');
            $table->renameColumn('test_ids_json', 'test_ids');
        });
    }

    public function down()
    {
        Schema::table('subparameters', function (Blueprint $table) {
            $table->string('parameter_id')->nullable();
            $table->text('test_ids')->nullable();
        });

        DB::statement("UPDATE subparameters SET parameter_id = JSON_UNQUOTE(JSON_EXTRACT(parameter_id, '$[0]'))");
        DB::statement("UPDATE subparameters SET test_ids = '[]'");

        Schema::table('subparameters', function (Blueprint $table) {
            $table->dropColumn(['parameter_id', 'test_ids']);
            $table->renameColumn('parameter_id_old', 'parameter_id');
            $table->renameColumn('test_ids_old', 'test_ids');
        });
    }
};