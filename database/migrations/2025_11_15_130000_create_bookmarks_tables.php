<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates event and ecospace bookmark tables with foreign keys and unique constraints.
     */
    public function up()
    {
        Schema::create('tbl_evbookmarks', function (Blueprint $table) {
            $table->increments('evBookmarkID');
            $table->unsignedBigInteger('userID');
            // tbl_events.eventID is created with increments() (unsigned integer)
            $table->unsignedInteger('eventID');
            $table->dateTime('dateCreated')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('eventID')->references('eventID')->on('tbl_events')->onDelete('cascade');

            $table->unique(['userID', 'eventID'], 'evbook_user_event_unique');
        });

        Schema::create('tbl_esbookmarks', function (Blueprint $table) {
            $table->increments('esBookmarkID');
            $table->unsignedBigInteger('userID');
            // ecospaces.ecospaceID is created with increments() (unsigned integer)
            $table->unsignedInteger('ecospaceID');
            $table->dateTime('dateCreated')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ecospaceID')->references('ecospaceID')->on('ecospaces')->onDelete('cascade');

            $table->unique(['userID', 'ecospaceID'], 'esbook_user_ecospace_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('tbl_esbookmarks');
        Schema::dropIfExists('tbl_evbookmarks');
    }
};
