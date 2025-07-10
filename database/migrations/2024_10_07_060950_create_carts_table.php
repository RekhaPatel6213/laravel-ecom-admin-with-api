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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('category_id');
            $table->integer('product_id');
            $table->string('product_code')->nullable();
            $table->integer('variant_id')->nullable();
            $table->string('name', 255);
            $table->string('variant_type', 50)->nullable();
            $table->string('variant_value', 50)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('seo_url')->nullable();
            $table->integer('quantity');
            $table->float('mrp');
            $table->float('selling_price');
            $table->float('gst_per')->nullable();
            $table->float('gst_val')->nullable();
            $table->float('with_out_gst_price')->nullable();
            $table->float('total_gst_val')->nullable();
            $table->float('amount');
            $table->float('amount_without_gst')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
