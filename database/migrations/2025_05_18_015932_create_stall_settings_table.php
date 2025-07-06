<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stall_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('profile_image')->nullable();
            $table->string('background_image')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->timestamps();
            $table->foreign('vendor_id')->references('vendor_id')->on('vendors')->onDelete('cascade');
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stall_settings');
    }
};