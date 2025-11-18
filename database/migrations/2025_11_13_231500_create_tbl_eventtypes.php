<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Insert predefined event types
        DB::table('tbl_eventtypes')->insert([
            ['eventTypeName' => 'Wildlife Safaris', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Game Drives', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Bird Watching', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Nature Walks', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Guided Hikes', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'River or Launch Cruises', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Rhino Tracking', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Cave Adventures', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Zip-Lining', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Coffee Experience Tours', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Cultural Experiences', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Workshops on Conservation', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Educational Seminars', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Community Clean-up Drives', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Tree Planting Days', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Yoga or Meditation Retreats', 'dateCreated' => Carbon::now()],
            ['eventTypeName' => 'Nature Photography Contests', 'dateCreated' => Carbon::now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_eventtypes');
    }
};
