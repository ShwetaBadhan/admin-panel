<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_add_logos_and_helpdesk_to_settings_table.php

public function up()
{
    Schema::table('settings', function (Blueprint $table) {
        $table->string('black_logo')->nullable();
        $table->string('white_logo')->nullable();
        $table->string('backend_logo')->nullable();
        $table->string('favicon')->nullable();
        $table->string('helpdesk_number')->nullable();
    });
}

public function down()
{
    Schema::table('settings', function (Blueprint $table) {
        $table->dropColumn(['black_logo', 'white_logo', 'backend_logo', 'favicon', 'helpdesk_number']);
    });
}
};
