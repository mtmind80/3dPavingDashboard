<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalDetailVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_detail_vehicles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_detail_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->string('vehicle_name',80)->nullable();
            $table->unsignedDouble('number_of_vehicles')->nullable();
            $table->unsignedDouble('days')->default(0);
            $table->unsignedDouble('hours')->nullable()->default(0);
            $table->float('rate_per_hour',8,2, true)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
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
        Schema::dropIfExists('proposal_detail_vehicles');
    }
}
