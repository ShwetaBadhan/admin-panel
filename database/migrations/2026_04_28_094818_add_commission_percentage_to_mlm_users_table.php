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
    Schema::table('mlm_users', function (Blueprint $table) {
        $table->tinyInteger('commission_percentage')->default(10)->after('membership_type');
    });
}

public function down(): void
{
    Schema::table('mlm_users', function (Blueprint $table) {
        $table->dropColumn('commission_percentage');
    });
}
};
