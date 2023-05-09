<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('type', 50);
            $table->string('description', 200);
        });
        
        // @todo add types
        

        $data =  array(
            [
                'type' => 'Ashpalt Vendor',
                'description' => 'Contractor that provides Asphalt Services',
            ],
            [
                'type' => 'Materials Vendor',
                'description' => 'Materials Vendor',
            ],
            [
                'type' => 'Office Services',
                'description' => 'Office Services',
            ],
        );
        foreach ($data as $datum){
            $populate = new \App\Models\VendorType();
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
        Schema::dropIfExists('vendor_types');
    }
}
