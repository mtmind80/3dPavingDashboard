<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_locations', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 200);
            $table->string('address', 200);
            $table->string('city', 200);
            $table->string('state', 200);
            $table->string('zip', 200);
            $table->string('phone', 200);
            $table->string('manager', 200);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_locations');
    }
}
