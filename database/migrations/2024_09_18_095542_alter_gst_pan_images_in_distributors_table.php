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
        Schema::table('distributors', function (Blueprint $table) {
            $table->renameColumn('pan_no_of_company', 'pan_no');
            $table->renameColumn('image', 'pan_doc');
            $table->string('shop_name', 150)->nullable()->change();

            $table->dropColumn(['default_address', 'website']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->renameColumn('pan_doc', 'image');
            $table->renameColumn('pan_no', 'pan_no_of_company');

            $table->boolean('default_address')->default(0)->comment('0 for Not Default Address, 1 for Default Address');
            $table->text('website')->nullable();
        });
    }
};
