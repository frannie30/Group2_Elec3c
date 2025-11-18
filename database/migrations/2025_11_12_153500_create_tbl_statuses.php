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
        Schema::create('tbl_statuses', function (Blueprint $table) {
            $table->increments('statusID');
            $table->string('status', 100);
            $table->timestamp('dateCreated')->useCurrent();
        });

        // Insert default statuses: 1-pending, 2-active, 3-archived
        DB::table('tbl_statuses')->insert([
            ['statusID' => 1, 'status' => 'pending',  'dateCreated' => Carbon::now()],
            ['statusID' => 2, 'status' => 'active',   'dateCreated' => Carbon::now()],
            ['statusID' => 3, 'status' => 'archived', 'dateCreated' => Carbon::now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_statuses');
    }
};
