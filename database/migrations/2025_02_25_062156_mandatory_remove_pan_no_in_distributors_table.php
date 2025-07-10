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
            $table->string('pan_no')->nullable()->change();
            $table->string('cst_gst_no')->nullable()->change();
            $table->string('address')->nullable()->change();
            // $table->string('area_id')->nullable()->change();
        });
    }
};
