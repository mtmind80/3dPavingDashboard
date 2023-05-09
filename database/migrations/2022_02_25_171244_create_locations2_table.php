<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocations2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations2', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('address_line1', 80);
            $table->string('address_line2', 80)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('state', 50)->default('Florida')->nullable();
            $table->string('postal_code', 25)->nullable();
            $table->string('county', 80)->nullable()->default("Broward");
            $table->unsignedBigInteger('location_type_id')->default(1);
            $table->string('parcel_number', 50)->nullable();
            $table->mediumText('note')->nullable();
            $table->string('original_table', 80)->nullable();
            $table->unsignedBigInteger('original_id')->nullable();
            $table->unsignedBigInteger('new_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations2');
    }
}
