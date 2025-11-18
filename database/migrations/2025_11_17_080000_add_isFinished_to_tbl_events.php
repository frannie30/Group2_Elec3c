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
        if (! Schema::hasColumn('tbl_events', 'isFinished')) {
            Schema::table('tbl_events', function (Blueprint $table) {
                $table->boolean('isFinished')->default(false)->after('isDone');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tbl_events', 'isFinished')) {
            Schema::table('tbl_events', function (Blueprint $table) {
                $table->dropColumn('isFinished');
            });
        }
    }
};
