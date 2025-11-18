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
        Schema::create('tbl_pricetiers', function (Blueprint $table) {
            $table->increments('priceTierID');
            // 'procetier' spelling included intentionally per diagram
            $table->string('pricetier', 100)->comment('Spelling from diagram');
            $table->timestamp('dateCreated')->useCurrent();
        });

        // Insert default price tiers: 1-$, 2-$$, 3-$$$
        DB::table('tbl_pricetiers')->insert([
            ['priceTierID' => 1, 'pricetier' => '$',   'dateCreated' => Carbon::now()],
            ['priceTierID' => 2, 'pricetier' => '$$',  'dateCreated' => Carbon::now()],
            ['priceTierID' => 3, 'pricetier' => '$$$', 'dateCreated' => Carbon::now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pricetiers');
    }
};
