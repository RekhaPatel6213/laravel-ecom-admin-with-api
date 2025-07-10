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
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn(['pincode', 'city_id']);
            $table->foreignId('distributor_id')->nullable()->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->index();
            $table->string('pincode', 10)->nullable();

            // Remove the newly added column
            $table->dropColumn('distributor_id');
        });
    }
};
