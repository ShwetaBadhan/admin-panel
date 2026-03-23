<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_independent_active_columns_to_captcha_settings_table.php

    public function up()
    {
        Schema::table('captcha_settings', function (Blueprint $table) {
            $table->boolean('cloudflare_active')->default(false)->after('is_active');
            $table->boolean('google_active')->default(false)->after('cloudflare_active');

            // Optional: drop old column if you don't need it
            // $table->dropColumn('is_active');
        });
    }

    public function down()
    {
        Schema::table('captcha_settings', function (Blueprint $table) {
            $table->dropColumn(['cloudflare_active', 'google_active']);
        });
    }
};
