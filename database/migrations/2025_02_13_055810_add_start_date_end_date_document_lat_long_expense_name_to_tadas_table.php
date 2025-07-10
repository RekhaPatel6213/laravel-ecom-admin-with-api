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
        Schema::table('tadas', function (Blueprint $table) {
            $table->dropColumn('value');
            $table->dropColumn('date');
            $table->dropColumn('amount');

            $table->date('start_date')->nullable()->after('tadatype_id');
            $table->date('end_date')->nullable()->after('start_date');
            $table->decimal('amount', 8, 2)->nullable()->after('end_date');
            $table->string('photo')->nullable()->after('amount');
            $table->string('lat')->nullable()->after('photo');
            $table->string('long')->nullable()->after('lat');
            $table->string('expense_name')->nullable()->after('long');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tadas', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('photo');
            $table->dropColumn('lat');
            $table->dropColumn('long');
            $table->dropColumn('expense_name');
        });
    }
};
