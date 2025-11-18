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
        Schema::create('tbl_reviews', function (Blueprint $table) {
            $table->increments('reviewID');
            $table->unsignedBigInteger('userID');
            $table->unsignedInteger('ecospaceID');
            $table->integer('rating');
            $table->text('review')->nullable();
            $table->timestamp('dateCreated')->useCurrent();
            $table->timestamp('dateUpdated')->useCurrent()->useCurrentOnUpdate();

            // Foreign keys
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            // ecospaces table is named `ecospaces` in this project
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
        Schema::dropIfExists('tbl_reviews');
    }
};
