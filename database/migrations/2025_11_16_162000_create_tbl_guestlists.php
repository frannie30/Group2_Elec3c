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
        Schema::create('tbl_guestlists', function (Blueprint $table) {
            $table->increments('guestID');
            $table->unsignedInteger('eventID');
            // users.id is a big increment (unsignedBigInteger)
            $table->unsignedBigInteger('userID');
            $table->boolean('isGoing')->default(false);
            $table->timestamp('dateCreated')->useCurrent();
            $table->timestamp('dateUpdated')->useCurrent()->useCurrentOnUpdate();

            // Foreign keys
            $table->foreign('eventID')->references('eventID')->on('tbl_events')->onDelete('cascade');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');

            // A user can only be on a guestlist once (unique per event)
            $table->unique(['eventID', 'userID'], 'tbl_guestlists_event_user_unique');
        });

        // Note: some DB-specific features (like commenting an index) are intentionally
        // omitted here to keep the migration portable across DB drivers.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_guestlists');
    }
};
