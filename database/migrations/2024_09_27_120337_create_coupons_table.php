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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->enum('type', config('constants.DEFAULT_COUPON_TYPES'))->default(config('constants.PERCENTADE'));
            $table->decimal('discount', 8, 2);
            $table->decimal('min_order_value', 8, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_coupon')->nullable();
            $table->integer('max_discount_allow')->nullable();
            $table->integer('coupon_use_time')->nullable();
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
        Schema::dropIfExists('coupons');
    }
};
