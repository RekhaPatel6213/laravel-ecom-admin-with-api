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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            // $table->foreignId('distributor_id')->index();
            $table->foreignId('route_id')->index();
            $table->datetime('start_time')->nullable();
            $table->string('start_latitude')->nullable();
            $table->string('start_longitude')->nullable();
            $table->text('start_location')->nullable();
            $table->datetime('end_time')->nullable();
            $table->string('end_latitude')->nullable();
            $table->string('end_longitude')->nullable();
            $table->text('end_location')->nullable();
            // $table->string('meeting_type');
            $table->datetime('meeting_date');
            $table->datetime('reminder_date')->nullable();
            $table->text('reminder_title')->nullable();
            $table->text('comments')->nullable();
            $table->text('attachments1')->nullable();
            $table->text('attachments2')->nullable();
            $table->text('attachments3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
