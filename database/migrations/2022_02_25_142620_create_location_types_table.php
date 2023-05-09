<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_types', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('type', 50);
            $table->string('description', 200);

        });

        $data = array(
            [
                'type' => 'Commercial',
                'description' => 'Commercial Property'
            ],
            [
                'type' => 'Residential',
                'description' => 'Residential Property'
            ],
            [
                'type' => 'Government',
                'description' => 'Government Or Municipal Property'
            ],
            [
                'type' => 'General',
                'description' => 'General Property'
            ],
            [
                'type' => 'Other',
                'description' => 'Other Property'
            ],

        );
        foreach($data as $datum) {
            $loc = new App\Models\LocationType();
            $loc->type = $datum['type'];
            $loc->description = $datum['description'];
            $loc->save();
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_types');
    }
}
