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
        Schema::create('usertype', function (Blueprint $table) {
            $table->string('userTypeID')->primary();
            $table->string('userTypeName');
            $table->timestamps();
        });

        // Insert default user types: 1-admin, 2-user, 3-owner
        DB::table('usertype')->insert([
            ['userTypeID' => '1', 'userTypeName' => 'admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['userTypeID' => '2', 'userTypeName' => 'user',  'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['userTypeID' => '3', 'userTypeName' => 'owner', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usertype');
    }
};
