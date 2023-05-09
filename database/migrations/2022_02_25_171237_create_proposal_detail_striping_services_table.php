<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalDetailStripingServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_detail_striping_services', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_detail_id');
            $table->unsignedBigInteger('striping_cost_id');
            $table->string('description',255);
            $table->integer('quantity')->unsigned()->default(0);
            $table->float('cost',8,2, true)->default(0); //money

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
        Schema::dropIfExists('proposal_detail_striping_services');
    }
}
