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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('zone_id')->after('designation_id')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
        });

        Schema::table('distributors', function (Blueprint $table) {
            $table->unsignedSmallInteger('zone_id')->after('pincode')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->unsignedSmallInteger('zone_id')->after('pincode')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropColumn('zone_id');
        });

        Schema::table('distributors', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropColumn('zone_id');
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropColumn('zone_id');
        });
    }
};
