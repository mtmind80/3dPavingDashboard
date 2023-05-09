<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalDetailAdditionalCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_detail_additional_costs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_detail_id');
            $table->unsignedBigInteger('created_by')->nullable()->default(1);
            $table->float('amount')->nullable()->default(0);
            $table->enum('type', ['Dump Fee', 'Other'])->default('Dump Fee');
            $table->mediumText('description')->nullable();
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
        Schema::dropIfExists('proposal_detail_additional_costs');
    }
}
