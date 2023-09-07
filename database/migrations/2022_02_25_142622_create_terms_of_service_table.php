<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsOfServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms_of_service', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->longText('text');
            $table->integer('section')->unsigned();
            $table->string('title', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terms_of_service');
    }
}
