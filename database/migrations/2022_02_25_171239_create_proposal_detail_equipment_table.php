<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalDetailEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_detail_equipment', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_detail_id');
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedDouble('hours')->nullable();
            $table->unsignedDouble('days')->nullable();
            $table->unsignedDouble('number_of_units')->nullable();
            $table->enum('rate_type',['per hour','per day']);
            $table->float('rate',8,2, true)->default(0);
            $table->timestamps();

            $table->foreign('proposal_detail_id')->references('id')->on('proposal_details')->onDelete('cascade');
            $table->foreign('equipment_id')->references('id')->on('equipment');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_detail_equipment');
    }
}
