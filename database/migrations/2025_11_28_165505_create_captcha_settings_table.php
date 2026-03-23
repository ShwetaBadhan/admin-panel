<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('captcha_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index(); // 'cloudflare' or 'google'
            $table->string('site_key');
            $table->string('secret_key');
            $table->string('domain')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

       
    }

    public function down(): void
    {
        Schema::dropIfExists('captcha_settings');
    }
};