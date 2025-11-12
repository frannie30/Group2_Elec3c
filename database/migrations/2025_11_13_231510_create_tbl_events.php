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
        Schema::create('tbl_events', function (Blueprint $table) {
            $table->increments('eventID');
            $table->string('eventName', 255);
            $table->unsignedInteger('eventTypeID');
            // users.id is a big increment, so use unsignedBigInteger for user FK
            $table->unsignedBigInteger('userID')->comment('The user/organizer who created the event');
            $table->string('eventAdd', 255)->comment('Address of the event');
            $table->unsignedInteger('statusID');
            $table->unsignedInteger('priceTierID');
            $table->dateTime('eventDate');
            $table->text('eventDesc')->nullable();
            $table->boolean('isDone')->default(false);
            $table->timestamp('dateCreated')->useCurrent();
            $table->timestamp('dateUpdated')->useCurrent()->useCurrentOnUpdate();
            // Soft deletes
            $table->softDeletes();

            // Foreign keys
            $table->foreign('eventTypeID')->references('eventTypeID')->on('tbl_eventtypes')->onDelete('restrict');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('statusID')->references('statusID')->on('tbl_statuses')->onDelete('restrict');
            $table->foreign('priceTierID')->references('priceTierID')->on('tbl_pricetiers')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_events');
    }
};
