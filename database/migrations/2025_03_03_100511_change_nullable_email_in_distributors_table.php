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
            $table->string('email')->nullable()->change();
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }
};
