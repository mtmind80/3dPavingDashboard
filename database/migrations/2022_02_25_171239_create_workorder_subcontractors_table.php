<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkorderSubcontractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workorder_subcontractors', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedBigInteger('proposal_detail_id');
            $table->unsignedBigInteger('contractor_id');
            $table->date('report_date');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->float('cost',8,2, true)->default(0);
            $table->string('description', 255)->nullable();
            $table->timestamps();
            

            $table->foreign('contractor_id')->references('id')->on('contractors');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workorder_subcontractors');
    }
}
