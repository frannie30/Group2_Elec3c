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
        Schema::create('tbl_eventtypes', function (Blueprint $table) {
            $table->increments('eventTypeID');
            $table->string('eventTypeName', 100);
            $table->timestamp('dateCreated')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_eventtypes');
    }
};
