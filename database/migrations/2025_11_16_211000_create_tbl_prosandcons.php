<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_prosandcons', function (Blueprint $table) {
            $table->increments('pcID');
            $table->boolean('isPro')->comment('1 for Pro, 0 for Con');
            $table->string('description', 255);
            // Link pros/cons to the user who wrote them
            $table->unsignedBigInteger('userID');
            $table->unsignedInteger('ecospaceID')->nullable();
            $table->timestamp('dateCreated')->useCurrent();
            $table->timestamp('dateUpdated')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ecospaceID')->references('ecospaceID')->on('ecospaces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_prosandcons');
    }
};
