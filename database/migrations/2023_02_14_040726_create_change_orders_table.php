<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('job_master_id',50)->default(null)->index()->nullable();
            $table->unsignedBigInteger('proposal_id')->index();
            $table->string('name', 225);
            $table->integer('change_order_status')->default(1);
            $table->boolean('mot_required')->default(false);
            $table->boolean('permit_required')->default(false);
            $table->boolean('nto_required')->default(false);
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('change_orders');
    }
}
