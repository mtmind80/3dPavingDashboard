<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_status', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('status', 25);
            $table->string('color', 6);
            
        });



        $data =  array(
            [
                'status' => 'NEW',
                'color' => 'D4E6F1',
            ],

            [
                'status' => 'ACTIVE',
                'color' => 'ABEBC6',
            ],

            [
                'status' => 'NOT ACTIVE',
                'color' => 'dedede',
            ],

            [
                'status' => 'ARCHIVED',
                'color' => 'd0d0d0',
            ],
        );
        foreach ($data as $datum){
            $table = new \App\Models\LeadStatus;
            $table->status =$datum['status'];
            $table->color =$datum['color'];
            $table->save();
        }

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_status');
    }
}
