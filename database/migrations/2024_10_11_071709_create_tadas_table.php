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
        Schema::create('tada_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->enum('type', [config('constants.PHOTO'), config('constants.KM')])->default(config('constants.KM'));
            $table->enum('status', config('constants.DEFAULT_STATUSES'))->default(config('constants.ACTIVE'));
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('tadatype_id')->index();
            $table->float('amount', 8, 2);
            $table->string('type');
            $table->string('value');
            $table->text('comment')->nullable();
            $table->date('date');
            $table->boolean('is_confirm')->default(0)->comment('0 for Not, 1 for Yes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tadas');
        Schema::dropIfExists('tada_types');
    }
};
