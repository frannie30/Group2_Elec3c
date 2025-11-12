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
        Schema::create('tbl_eventImages', function (Blueprint $table) {
            $table->increments('eventImageID');
            $table->unsignedInteger('eventID');
            $table->string('path');
            $table->integer('order')->default(0);
            $table->string('caption')->nullable();
            $table->timestamps();

            $table->foreign('eventID')->references('eventID')->on('tbl_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_eventImages');
    }
};
