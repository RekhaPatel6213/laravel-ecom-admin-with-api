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
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('distributor_id')->index()->after('user_id');
            $table->foreignId('shop_id')->index()->nullable()->after('distributor_id');
            $table->foreignId('meeting_id')->index()->nullable()->after('shop_id');
            $table->float('cgst_per')->nullable()->after('gst_val');
            $table->float('cgst_val')->nullable()->after('cgst_per');
            $table->float('sgst_per')->nullable()->after('cgst_val');
            $table->float('sgst_val')->nullable()->after('sgst_per');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['distributor_id', 'shop_id', 'meeting_id', 'cgst_per', 'cgst_val', 'sgst_per', 'sgst_val']);
        });
    }
};
