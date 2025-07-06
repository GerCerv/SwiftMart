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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id'); // Must match the vendors primary key type
            $table->string('product_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->decimal('price', 8, 2);
            $table->integer('stock');
            $table->integer('discount')->default(0);
            $table->string('image')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();
            $table->string('image5')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        
            // Correct foreign key reference
            $table->foreign('vendor_id')
                  ->references('vendor_id')  // This must match the primary key in vendors
                  ->on('vendors')
                  ->onDelete('cascade'); // Optional: delete products when vendor is deleted
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
