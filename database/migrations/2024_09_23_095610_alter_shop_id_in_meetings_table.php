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
        Schema::table('meetings', function (Blueprint $table) {
            $table->foreignId('shop_id')->index()->after('distributor_id');

            $table->unsignedSmallInteger('type_id')->nullable()->index()->after('shop_id');
            $table->foreign('type_id')->references('id')->on('meeting_types')->onDelete('cascade');

            $table->renameColumn('attachments1', 'attachment1');
            $table->renameColumn('attachments2', 'attachment2');
            $table->renameColumn('attachments3', 'attachment3');

            $table->dropColumn(['reminder_date', 'reminder_title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            // $table->dropForeign(['shop_id']);
            $table->dropForeign(['type_id']);
            $table->dropColumn(['shop_id', 'type_id']);

            $table->renameColumn('attachment1', 'attachments1');
            $table->renameColumn('attachment2', 'attachments2');
            $table->renameColumn('attachment3', 'attachments3');

            $table->datetime('reminder_date')->nullable()->after('meeting_date');
            $table->text('reminder_title')->nullable()->after('reminder_date');
        });
    }
};
