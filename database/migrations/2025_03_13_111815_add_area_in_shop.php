<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('shop_area')->after('area_id')->nullable();
        });
        DB::statement("UPDATE shops SET country_id = 1 WHERE country_id IS NULL");

        // Now, set the default for future inserts
        DB::statement("ALTER TABLE shops ALTER COLUMN country_id SET DEFAULT 1");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('shop_area');
        });
        DB::statement("ALTER TABLE shops ALTER COLUMN country_id DROP DEFAULT");

    }
};
