<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     * Listing of services in a proposal
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_details', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedBigInteger('change_order_id')->nullable()->default(null);
            $table->unsignedBigInteger('services_id')->default(0);
            $table->unsignedBigInteger('contractor_id')->nullable()->default(null);
            $table->string('contractor_bid',191)->nullable()->default(null);
            $table->unsignedBigInteger('status_id')->default(1);
            $table->unsignedBigInteger('location_id')->nullable()->default(null);
            $table->unsignedBigInteger('fieldmanager_id')->nullable()->default(null);
            $table->unsignedBigInteger('second_fieldmanager_id')->nullable()->default(null);
            $table->decimal('cost',14,2, true)->nullable()->default(0);
            $table->decimal('material_cost',13,2, true)->nullable()->default(0);
            $table->string('service_name', 250)->nullable();
            $table->mediumText('service_desc')->nullable();
            $table->boolean('bill_after')->default(false);
            $table->integer('dsort')->unsigned()->nullable()->default(0);
            $table->double('linear_feet')->unsigned()->nullable()->default(0);
            $table->double('cost_per_linear_feet')->unsigned()->nullable()->default(0);
            $table->double('square_feet',)->unsigned()->nullable()->default(0);
            $table->double('square_yards')->unsigned()->nullable()->default(0);
            $table->double('cubic_yards')->unsigned()->nullable()->default(0);
            $table->double('tons')->unsigned()->nullable()->default(0);
            $table->double('loads')->unsigned()->nullable()->default(0);
            $table->double('locations')->unsigned()->nullable()->default(0);
            $table->double('depth')->unsigned()->nullable()->default(0);
            $table->double('profit')->nullable()->default(0);
            $table->double('days')->unsigned()->nullable()->default(0);
            $table->double('cost_per_day')->unsigned()->nullable()->default(0);
            $table->double('break_even')->nullable()->default(0);
            $table->double('primer')->unsigned()->nullable()->default(0);
            $table->double('yield')->unsigned()->nullable()->default(0);
            $table->double('fast_set')->unsigned()->nullable()->default(0);
            $table->double('additive')->unsigned()->nullable()->default(0);
            $table->double('sealer')->unsigned()->nullable()->default(0);
            $table->double('sand')->unsigned()->nullable()->default(0);
            $table->double('phases')->unsigned()->nullable()->default(0);
            $table->double('overhead')->nullable()->default(0);
            $table->integer('catchbasins')->unsigned()->nullable()->default(0);
            $table->mediumText('proposal_text')->nullable();
            $table->mediumText('alt_desc')->nullable();
            $table->mediumText('proposal_note')->nullable();
            $table->mediumText('proposal_field_note')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('scheduled_by')->nullable()->default(null);
            $table->unsignedBigInteger('completed_by')->nullable()->default(null);
            $table->string('completed_date', 25)->nullable();
            $table->string('start_date', 25)->nullable();
            $table->string('end_date', 25)->nullable();

            $table->string('old_status',50)->nullable();
            $table->unsignedBigInteger('old_vendor_id')->nullable()->default(null);
            $table->unsignedBigInteger('old_detail_id')->nullable()->default(null);
            $table->unsignedBigInteger('old_proposal_id')->nullable()->default(null);

            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('proposals');
            
            
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_details');
    }
}
