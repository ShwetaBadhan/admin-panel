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
        Schema::create('wallet_charges', function (Blueprint $table) {
    $table->id();
    $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
    $table->string('charge_type'); // withdrawal_fee, transfer_fee, maintenance_fee
    $table->enum('charge_mode', ['PERCENTAGE', 'FIXED'])->default('FIXED');
    $table->decimal('charge_value', 10, 2);
    $table->decimal('min_charge', 10, 2)->default(0);
    $table->decimal('max_charge', 10, 2)->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_charges');
    }
};
