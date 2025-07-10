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
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('email')->unique();
            $table->string('mobile', 20)->unique()->nullable();

            $table->string('shop_name', 150);
            $table->string('pan_no_of_company', 50);
            $table->string('vat_tin_no', 50)->nullable();
            $table->string('cst_gst_no', 50);
            $table->text('image')->nullable();
            $table->text('gst_doc')->nullable();
            $table->text('website')->nullable();

            $table->text('address');
            $table->foreignId('country_id')->constrained();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('city_id')->constrained();
            $table->foreignId('area_id')->constrained()->nullable();
            $table->string('pincode', 10)->nullable();
            $table->boolean('default_address')->default(0)->comment('0 for Not Default Address, 1 for Default Address');
            $table->enum('status', config('constants.DEFAULT_STATUSES'))->default(config('constants.ACTIVE'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};
