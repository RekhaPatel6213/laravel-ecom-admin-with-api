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
            $table->dropColumn('type');

            $table->boolean('is_date')->default(0)->comment('0 for Not, 1 for Yes')->after('name');
            $table->boolean('is_amount')->default(0)->comment('0 for Not, 1 for Yes')->after('is_date');
            $table->boolean('is_photo')->default(0)->comment('0 for Not, 1 for Yes')->after('is_amount');
            $table->boolean('is_location')->default(0)->comment('0 for Not, 1 for Yes')->after('is_photo');
            $table->boolean('is_expense_name')->default(0)->comment('0 for Not, 1 for Yes')->after('is_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tada_types', function (Blueprint $table) {
            $table->dropColumn('is_date');
            $table->dropColumn('is_amount');
            $table->dropColumn('is_photo');
            $table->dropColumn('is_location');
            $table->dropColumn('is_expense_name');
        });
    }
};
