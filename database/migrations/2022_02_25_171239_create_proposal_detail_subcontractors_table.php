<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalDetailSubcontractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_detail_subcontractors', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_detail_id');
            $table->unsignedBigInteger('contractor_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->double('cost')->nullable()->default(0);
            $table->double('overhead')->unsigned()->nullable()->default(0);
            $table->boolean('havebid')->default(0);
            $table->boolean('accepted')->nullable()->default(0);
            $table->string('attached_bid', 255)->nullable();
            $table->string('description', 255)->nullable();
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
        Schema::dropIfExists('proposal_detail_subcontractors');
    }
}
