<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractorTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contractor_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('type', 50);
            $table->string('description', 200);
        });
        
        // @todo add types
        

        $data =  array(
            [
                'type' => 'General Sub Contractor',
                'description' => 'Contractor that provides General Services',
            ],
            [
                'type' => 'Asphalt Sub Contractor',
                'description' => 'Contractor that provides Asphalt Services',
            ],
            [
                'type' => 'Concrete Sub Contractor',
                'description' => 'Contractor that provides Concrete Services',
            ],
        );
        foreach ($data as $datum){
            $populate = new \App\Models\ContractorType();
            $populate->type =$datum['type'];
            $populate->description =$datum['description'];
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
        Schema::dropIfExists('contractor_types');
    }
}
