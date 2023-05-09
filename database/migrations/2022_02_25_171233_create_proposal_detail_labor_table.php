<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalDetailLaborTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_detail_labor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_detail_id');
            $table->string('labor_name',50);
            $table->float('rate_per_hour',8,2, true); //money
            $table->integer('number')->default(0);
            $table->unsignedDouble('days')->default(0);
            $table->unsignedDouble('hours')->default(0);
            $table->unsignedBigInteger('created_by')->default(1);
            $table->timestamps();

            $table->foreign('proposal_detail_id')->references('id')->on('proposal_details')->onDelete('cascade');

            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_detail_labor');
    }
}
