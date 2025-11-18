<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
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
        // Use raw SQL to avoid requiring doctrine/dbal for column modifications
        DB::statement('ALTER TABLE tbl_reviews MODIFY ecospaceID INT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE tbl_reviews MODIFY ecospaceID INT UNSIGNED NOT NULL');
    }
};
