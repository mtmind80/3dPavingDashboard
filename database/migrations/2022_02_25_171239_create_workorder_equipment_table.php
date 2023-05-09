<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkorderEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workorder_equipment', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedBigInteger('proposal_detail_id');
            $table->unsignedBigInteger('equipment_id');
            $table->string('name',50);
            $table->date('report_date');
            $table->unsignedBigInteger('created_by');
            $table->unsignedDouble('hours')->nullable();
            $table->unsignedDouble('number_of_units')->nullable();
            $table->float('rate_per_hour',8,2, true)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workorder_equipment');
    }
}
