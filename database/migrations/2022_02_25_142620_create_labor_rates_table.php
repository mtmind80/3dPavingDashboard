<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaborRatesTable extends Migration
{
    /**
     * Run the migrations.
     * What we charge customers
     * @return void
     */
    public function up()
    {
        Schema::create('labor_rates', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name','80');
            $table->float('rate',8,2, true); //money

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('labor_rates');
    }
}
