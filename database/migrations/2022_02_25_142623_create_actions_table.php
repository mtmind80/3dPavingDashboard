<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('action',80)->unique();
        });

        $data =  array(
            ['milestone' => 'Proposal Created'],
            ['milestone' => 'Proposal Sent'],
            ['milestone' => 'Proposal Approved'],
            ['milestone' => 'Proposal Rejected'],
            ['milestone' => 'Proposal Signed'],
            ['milestone' => 'Set Alert'],
            ['milestone' => 'Remove Alert'],
            ['milestone' => 'MOT Sent'],
            ['milestone' => 'NTO Sent'],
            ['milestone' => 'Permit Filed'],
            ['milestone' => 'Permit Updated'],
            ['milestone' => 'Permit Completed'],
            ['milestone' => 'Ready To Close'],
            ['milestone' => 'Job Completed'],
            ['milestone' => 'Ready to Bill'],
            ['milestone' => 'Job Interim Billed'],
            ['milestone' => 'Job Billed'],
            ['milestone' => 'Job Canceled'],
            ['milestone' => 'Other Action'],
        );
        foreach ($data as $datum){
            $milestone = new App\Models\Actions;
            $milestone->action =$datum['milestone'];
            $milestone->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
    }
}
