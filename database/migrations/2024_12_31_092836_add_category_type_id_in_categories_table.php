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
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedTinyInteger('category_type_id')->after('parent_category_id')->nullable();
            $table->foreign('category_type_id')->references('id')->on('category_types')->onDelete('cascade');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedTinyInteger('category_type_id')->after('id')->nullable();
            $table->foreign('category_type_id')->references('id')->on('category_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['category_type_id']);
            $table->dropColumn('category_type_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_type_id']);
            $table->dropColumn('category_type_id');
        });
    }
};
