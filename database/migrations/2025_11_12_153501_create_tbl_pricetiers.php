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
        Schema::create('tbl_pricetiers', function (Blueprint $table) {
            $table->increments('priceTierID');
            // 'procetier' spelling included intentionally per diagram
            $table->string('pricetier', 100)->comment('Spelling from diagram');
            $table->timestamp('dateCreated')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pricetiers');
    }
};
