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
        Schema::table('tada_types', function (Blueprint $table) {
            $table->boolean('is_from_to_location')->default(0)->comment('0 for Not, 1 for Yes')->after('is_location');
            $table->boolean('is_km')->default(0)->comment('0 for Not, 1 for Yes')->after('is_from_to_location');
        });

        Schema::table('tadas', function (Blueprint $table) {
            $table->integer('km')->nullable()->after('date');
            $table->decimal('per_km_price', 8, 2)->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tada_types', function (Blueprint $table) {
            $table->dropColumn(['is_km', 'is_from_to_location']);
        });

        Schema::table('tadas', function (Blueprint $table) {
            $table->dropColumn(['km', 'per_km_price']);
        });
    }
};
