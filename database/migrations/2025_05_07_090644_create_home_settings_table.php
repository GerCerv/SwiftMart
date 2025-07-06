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
        Schema::create('home_settings', function (Blueprint $table) {
            $table->id();
            $table->json('carousel_items')->nullable();
            $table->json('featured_products')->nullable();
            $table->json('hot_deals')->nullable();
            $table->json('discounts')->nullable();
            $table->json('fresh_picks')->nullable();
            $table->json('promotional_section')->nullable();
            $table->json('discount_banner')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_settings');
    }
};
