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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->foreignId('distributor_id')->index();
            $table->string('email')->unique();
            $table->string('mobile', 20)->unique()->nullable();
            $table->text('website')->nullable();
            $table->text('address');
            $table->foreignId('country_id')->constrained();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('city_id')->constrained();
            $table->foreignId('area_id')->constrained()->nullable();
            $table->string('pincode', 10)->nullable();
            $table->enum('status', config('constants.DEFAULT_STATUSES'))->default(config('constants.ACTIVE'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
