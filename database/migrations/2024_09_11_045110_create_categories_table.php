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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('is_parent')->nullable()->comment('1 for Yes, 0 for No');
            $table->integer('parent_category_id')->nullable();
            $table->longText('description')->nullable();
            $table->text('image')->nullable();
            $table->text('app_image')->nullable();
            $table->text('banner_image')->nullable();
            $table->string('seo_keyword')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('schema_tag')->nullable();
            $table->integer('sort_order')->nullable();
            $table->enum('status', config('constants.DEFAULT_STATUSES'))->default(config('constants.ACTIVE'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
