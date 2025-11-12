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
        // Create ecospace images table
        Schema::create('tbl_esImages', function (Blueprint $table) {
            $table->increments('esImageID');
            // Match ecospaces primary key (ecospaceID -> unsigned integer)
            $table->unsignedInteger('ecospaceID');
            $table->string('path');
            $table->integer('order')->default(0);
            $table->string('caption')->nullable();
            $table->timestamps();

            $table->foreign('ecospaceID')
                ->references('ecospaceID')
                ->on('ecospaces')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_esImages');
    }
};
