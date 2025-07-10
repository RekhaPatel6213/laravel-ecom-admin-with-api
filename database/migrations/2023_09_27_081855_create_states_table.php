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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('code', 5)->nullable();
            $table->unsignedSmallInteger('zone_id')->nullable();
            $table->integer('sort_order')->nullable();
            $table->enum('status', config('constants.DEFAULT_STATUSES'))->default(config('constants.ACTIVE'));
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
