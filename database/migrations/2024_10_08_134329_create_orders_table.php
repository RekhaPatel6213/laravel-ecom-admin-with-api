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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->foreignId('distributor_id')->index();
            $table->foreignId('shop_id')->index()->nullable();
            $table->foreignId('meeting_id')->index()->nullable();
            $table->string('order_no', 150)->nullable();
            $table->string('invoice_no', 150)->nullable();
            $table->string('firstname', 150)->nullable();
            $table->string('lastname', 150)->nullable();
            $table->string('email', 150)->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->integer('shipping_address_id')->nullable();
            $table->string('shipping_firstname')->nullable();
            $table->string('shipping_lastname')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_mobile')->nullable();
            $table->string('shipping_country_name')->nullable();
            $table->string('shipping_state_name')->nullable();
            $table->string('shipping_city_name')->nullable();
            $table->string('shipping_pincode')->nullable();
            $table->integer('billing_address_id')->nullable();
            $table->string('billing_firstname')->nullable();
            $table->string('billing_lastname')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_mobile')->nullable();
            $table->string('billing_country_name')->nullable();
            $table->string('billing_state_name')->nullable();
            $table->string('billing_city_name')->nullable();
            $table->string('billing_pincode')->nullable();
            $table->string('payment_method')->nullable()->comment('Online or COD');
            $table->string('payment_code')->nullable();
            $table->integer('orderstatus_id')->nullable();
            $table->integer('is_paid')->nullable()->default(0);
            $table->float('sub_total', 8, 2)->nullable();
            $table->float('total_gst', 8, 2)->nullable();
            $table->float('cgst', 8, 2)->nullable();
            $table->float('sgst', 8, 2)->nullable();
            $table->float('shipping_charge', 8, 2)->nullable();
            $table->float('grand_total', 8, 2)->nullable();
            $table->integer('total_quantity')->nullable();
            $table->string('order_pdf')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->float('coupon_discount', 8, 2)->nullable();
            $table->string('payment_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('variant_id')->nullable();
            $table->string('variant_type')->nullable();
            $table->string('variant_value')->nullable();
            $table->integer('product_quantity')->nullable();
            $table->text('product_image')->nullable();
            $table->float('product_mrp', 8, 2)->nullable();
            $table->float('product_selling_price', 8, 2)->nullable();
            $table->float('gst_per', 8, 2)->nullable();
            $table->float('gst_val', 8, 2)->nullable();
            $table->float('total_amount', 8, 2)->nullable();
            $table->float('amount_without_gst', 8, 2)->nullable();
            $table->float('total_gst_val', 8, 2)->nullable();
            $table->float('with_out_gst_price', 8, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('orderstatus_id')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_histories');
        Schema::dropIfExists('order_products');
        Schema::dropIfExists('orders');
    }
};
