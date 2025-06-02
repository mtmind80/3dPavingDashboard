<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkorderFieldReportsTable extends Migration
{
    public function up()
    {
        Schema::create('workorder_field_reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_id')->index();
            $table->unsignedBigInteger('proposal_detail_id')->index();
            $table->unsignedBigInteger('created_by')->index();
            $table->date('report_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('workorder_field_reports');
    }

}
