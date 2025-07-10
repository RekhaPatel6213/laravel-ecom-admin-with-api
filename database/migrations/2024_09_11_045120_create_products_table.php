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
            $table->integer('category_id')->nullable();
            $table->integer('is_parent')->nullable()->comment('1 for Yes, 0 for No');
            $table->integer('product_id')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->text('image')->nullable();
            $table->string('alt_tag')->nullable();
            $table->text('description')->nullable();
            $table->text('specification')->nullable();
            $table->boolean('is_fast_selling')->default(0)->comment('0 for Inactive, 1 for Active');
            $table->integer('stock_status')->nullable()->comment('0 for Out of Stock, 1 for In Stock');
            $table->float('gst', 8, 2)->default(0.00)->nullable();
            $table->float('cgst', 8, 2)->default(0.00)->nullable();
            $table->float('sgst', 8, 2)->default(0.00)->nullable();
            $table->string('seo_keyword')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('schema_tag')->nullable();
            $table->integer('sort_order')->nullable();
            $table->enum('status', config('constants.DEFAULT_STATUSES'))->default(config('constants.ACTIVE'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
