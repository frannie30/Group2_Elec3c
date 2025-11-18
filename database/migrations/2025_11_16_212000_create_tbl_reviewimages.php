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
        Schema::create('tbl_reviewimages', function (Blueprint $table) {
            $table->increments('revImgID');
            $table->string('revImgName', 255)->comment('Image file name or URL');
            $table->unsignedInteger('reviewID');
            $table->timestamp('dateCreated')->useCurrent();

            // Foreign key to tbl_reviews.reviewID
            $table->foreign('reviewID')->references('reviewID')->on('tbl_reviews')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_reviewimages');
    }
};
