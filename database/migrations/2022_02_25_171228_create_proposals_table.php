<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('job_master_id',50)->default(null)->index()->nullable();
            $table->string('name', 225);
            $table->unsignedBigInteger('proposal_statuses_id')->default('1');
            $table->string('rejected_reason', 225);
            $table->dateTime('proposal_date')->default(date("Y-m-d H:i:s"));
            $table->dateTime('sale_date')->nullable()->default(null);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by')->nullable()->default(null);
            $table->unsignedBigInteger('contact_id')->index();
            $table->unsignedBigInteger('customer_staff_id')->nullable()->default(null);
            $table->unsignedBigInteger('salesmanager_id')->nullable()->default(10)->index();
            $table->unsignedBigInteger('salesperson_id')->nullable()->default(null)->index();
            $table->unsignedBigInteger('location_id')->nullable()->default(null);
            $table->unsignedBigInteger('lead_id')->nullable()->default(null);
            $table->unsignedBigInteger('changeorder')->nullable()->default(null);
            $table->unsignedInteger('discount')->nullable()->default(null);
            $table->boolean('progressive_billing')->default(false);
            $table->boolean('mot_required')->default(false);
            $table->boolean('permit_required')->default(false);
            $table->boolean('nto_required')->default(false);
            $table->boolean('on_alert')->default(false);
            $table->unsignedBigInteger('old_id')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('location_id')->references('id')->on('locations');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposals');
    }
}
