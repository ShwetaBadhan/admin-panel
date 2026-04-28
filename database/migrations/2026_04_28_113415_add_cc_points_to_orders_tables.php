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
    // Add CC points to orders table
    Schema::table('orders', function (Blueprint $table) {
        $table->decimal('total_cc_points', 10, 2)->default(0)->after('total_amount');
    });

    // Add CC points to order_items table
    Schema::table('order_items', function (Blueprint $table) {
        $table->decimal('cc_points', 10, 2)->default(0)->after('price');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('total_cc_points');
    });

    Schema::table('order_items', function (Blueprint $table) {
        $table->dropColumn('cc_points');
    });
}
};
