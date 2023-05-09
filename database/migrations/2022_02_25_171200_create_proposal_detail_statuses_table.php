<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalDetailStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_detail_statuses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('status', 50);
        });

        $data =  array(
            [
                'status' => 'Not Scheduled'
            ],
            [
                'status' => 'Scheduled'
            ],
            [
                'status' => 'Completed'
            ],
            [
                'status' => 'Cancelled'
            ],
        );
        foreach ($data as $datum){
            $populate = new \App\Models\ProposalDetailStatus();
            $populate->status =$datum['status'];
            $populate->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_detail_statuses');
    }
}
