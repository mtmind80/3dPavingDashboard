<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkorderTimesheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workorder_timesheets', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedBigInteger('proposal_detail_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('created_by');
            $table->date('report_date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->unsignedDouble('actual_hours')->default(0);
            $table->float('rate',8,2, true); //money
            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('proposals');
            $table->foreign('proposal_detail_id')->references('id')->on('proposal_details');
            $table->foreign('employee_id')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workorder_timesheets');
    }
}
