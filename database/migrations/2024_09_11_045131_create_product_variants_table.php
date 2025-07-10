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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->text('product_name');
            $table->unsignedSmallInteger('variant_type')->nullable()->index();
            $table->unsignedSmallInteger('variant_value')->nullable()->index();
            $table->integer('qty')->default(0)->comment('Product varient qantity');
            $table->float('mrp', 8, 2)->default(0.00)->nullable();
            $table->float('sp', 8, 2)->default(0.00)->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_type')->references('id')->on('variant_types')->onDelete('cascade');
            $table->foreign('variant_value')->references('id')->on('variant_values')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
