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
        Schema::table('payout_balances', function (Blueprint $table) {
            $table->decimal('left_cc', 12, 2)->default(0)->after('cc_balance');
            $table->decimal('right_cc', 12, 2)->default(0)->after('left_cc');
            $table->decimal('total_matched_cc', 12, 2)->default(0)->after('right_cc'); // Tracking ke liye
        });
    }

    public function down(): void
    {
        Schema::table('payout_balances', function (Blueprint $table) {
            $table->dropColumn(['left_cc', 'right_cc', 'total_matched_cc']);
        });
    }
};
