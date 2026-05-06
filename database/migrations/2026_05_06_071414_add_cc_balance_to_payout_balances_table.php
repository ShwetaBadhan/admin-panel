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
            $table->decimal('cc_balance', 12, 2)->default(0)->after('total_earned');
            $table->decimal('cc_rate_snapshot', 8, 2)->nullable()->after('cc_balance'); // Optional: rate at time of earning
        });
    }

    public function down(): void
    {
        Schema::table('payout_balances', function (Blueprint $table) {
            $table->dropColumn(['cc_balance', 'cc_rate_snapshot']);
        });
    }
};
