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
      Schema::table('order_items', function (Blueprint $table) {
    $table->decimal('commission_earned', 10, 2)->default(0)->after('cc_points');
    $table->decimal('extra_bonus', 10, 2)->default(0)->after('commission_earned');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            //
        });
    }
};
