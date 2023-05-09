<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 100);
            $table->string('color', 25)->nullable();

        });


        
        $data =  array(
            [
                'role' => 'Asphalt',
                'color' => '#0088cc',

            ],
            [
                'role' => 'Concrete',
                'color' => '#00CC00',

            ],
            [
                'role' => 'Drainage and Catchbasins',
                'color' => '#ffffff',

            ],
            [
                'role' => 'Excavation',
                'color' => '#dddd00',

            ],
            [
                'role' => 'Other',
                'color' => '#dododo',

            ],
            [
                'role' => 'Paver Brick',
                'color' => '#e3e3e3',

            ],
            [
                'role' => 'Rock',
                'color' => '#FF8C00',

            ],
            [
                'role' => 'Seal Coating',
                'color' => '#db5140',

            ],
            [
                'role' => 'Striping',
                'color' => '#0088EE',

            ],
            [
                'role' => 'Sub Contractor',
                'color' => '#0085DD',

            ],
        );
        foreach ($data as $datum){
            $role = new App\Models\ServiceCategory();
            $role->name =$datum['role'];
            $role->color =$datum['color'];
            $role->save();
        }
        //Add services
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_categories');
    }
}
