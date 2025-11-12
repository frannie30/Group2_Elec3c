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
        // Creating ecospaces table
        Schema::create('ecospaces', function (Blueprint $table) {
            // Primary key as specified
            $table->increments('ecospaceID');

            // Basic fields
            $table->string('ecospaceName');
            $table->string('ecospaceAdd')->comment('Address of the ecospace');
            $table->text('ecospaceDesc')->nullable();

            // Relations and status/price tier
            $table->unsignedBigInteger('userID')->comment('The user/owner who listed this space');
            // Use unsignedInteger to match the increments() PK on referenced tables
            $table->unsignedInteger('statusID');
            $table->unsignedInteger('priceTierID');

            // Timestamps with DB defaults (dateCreated/dateUpdated)
            $table->timestamp('dateCreated')->useCurrent();
            $table->timestamp('dateUpdated')->useCurrent()->useCurrentOnUpdate();

            // Hours/days
            $table->time('openingHours')->nullable();
            $table->time('closingHours')->nullable();
            $table->string('daysOpened', 100)->nullable()->comment('e.g., Mon-Fri');

            // Soft deletes
            $table->softDeletes();

            // Foreign keys
            // users.id is created with big increments ($table->id()) so use unsignedBigInteger above
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('statusID')->references('statusID')->on('tbl_statuses')->onDelete('restrict');
            $table->foreign('priceTierID')->references('priceTierID')->on('tbl_pricetiers')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecospaces');
    }
};
