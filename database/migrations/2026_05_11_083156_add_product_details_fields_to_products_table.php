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
    Schema::table('products', function (Blueprint $table) {
        $table->text('uses')->nullable()->after('description');
        $table->text('directions_for_use')->nullable()->after('uses');
        $table->text('cautions')->nullable()->after('directions_for_use');
        $table->text('primary_benefits')->nullable()->after('cautions');
        $table->text('ingredients')->nullable()->after('primary_benefits');
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['uses', 'directions_for_use', 'cautions', 'primary_benefits', 'ingredients']);
    });
}
};
