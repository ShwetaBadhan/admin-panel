<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subparameters', function (Blueprint $table) {
            // Add missing columns that your app uses
            $table->decimal('price', 10, 2)->default(0.00)->after('slug');
            $table->string('image')->nullable()->after('price');
            
            // Make sure these are JSON (in case not converted properly before)
            if (Schema::hasColumn('subparameters', 'parameter_id')) {
                $table->json('parameter_id')->nullable()->change();
            }
            if (Schema::hasColumn('subparameters', 'test_ids')) {
                $table->json('test_ids')->nullable()->change();
            }
        });
    }

    public function down()
    {
        Schema::table('subparameters', function (Blueprint $table) {
            $table->dropColumn(['price', 'image']);
        });
    }
};