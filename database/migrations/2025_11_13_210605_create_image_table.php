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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            // Match ecospaces primary key (ecospaceID -> unsigned integer)
            $table->unsignedInteger('ecospaceID');
            $table->string('path');
            $table->integer('order')->default(0);
            $table->string('caption')->nullable();
            $table->timestamps();

            // Explicit FK referencing ecospaces.ecospaceID
            $table->foreign('ecospaceID')->references('ecospaceID')->on('ecospaces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
