<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_types', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 50);
            $table->mediumText('description')->nullable();
            $table->float('rate', 8, 2, true)->default(0);
            $table->unsignedBigInteger('old_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_types');
    }
}
