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
            $table->text('from')->nullable()->after('tadatype_id');
            $table->text('to')->nullable()->after('from');
            $table->date('date')->nullable()->after('to');

            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tadas', function (Blueprint $table) {
            $table->dropColumn('from');
            $table->dropColumn('to');
            $table->dropColumn('date');

            $table->date('start_date')->nullable()->after('tadatype_id');
            $table->date('end_date')->nullable()->after('start_date');
        });
    }
};
