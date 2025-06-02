<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkorderAdditionalCostsTable extends Migration
{
    public function up()
    {
        Schema::create('workorder_additional_costs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_id')->index();
            $table->unsignedBigInteger('proposal_detail_id')->index();
            $table->unsignedBigInteger('workorder_field_report_id')->index()->nullable();
            $table->unsignedBigInteger('created_by')->index();
            $table->double('cost');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('workorder_additional_costs');
    }

}
