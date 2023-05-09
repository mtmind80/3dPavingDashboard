<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Material;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 150);
            $table->float('cost', 8, 2, true)->default(0);
            $table->unsignedBigInteger('service_category_id');
            //$table->float('alt_cost',8,2,true)->default(0);
            //$table->unsignedBigInteger('old_id')->default(0);
        });

        $data = array(
            [
                'name' => "Sealer (per gal)",
                'cost' => '4.40',
                'service_category_id' => '8'
            ],
            [
                'name' => "Sand (lbs.)",
                'cost' => 0.15,
                'service_category_id' => 8
            ],
            [
                'name' => "Additive (per gal)",
                'cost' => 18.00,
                'service_category_id' => 8
            ],
            [
                'name' => "Oil Spot Primer (per gal)",
                'cost' => 18.00,
                'service_category_id' => 8
            ],
            [
                'name' => "Fastset (per gal)",
                'cost' => 16.00,
                'service_category_id' => 8
            ],
            [
                'name' => "Base Rock (Broward & Dade) per ton",
                'cost' => 31.00,
                'service_category_id' => 7
            ],
            [
                'name' => "Base Rock (Palm Beach) per ton",
                'cost' => 41.00,
                'service_category_id' => 7
            ],
            [
                'name' => "Asphalt per ton",
                'cost' => 103.00,
                'service_category_id' => 1
            ],
            [
                'name' => "Concrete (Curb Mix) per cubic yard",
                'cost' => 160.00,
                'service_category_id' => 2
            ],
            [
                'name' => "Concrete (Drum Mix) per cubic yard",
                'cost' => 160.00,
                'service_category_id' => 2
            ],
            [
                'name' => "Mesh - Fiber per sq ft",
                'cost' => 8.50,
                'service_category_id' => 8
            ],
            [
                'name' => "Mesh - Wire per sq ft",
                'cost' => 7.50,
                'service_category_id' => 7
            ],
            [
                'name' => "Rebar Per linear Foot",
                'cost' => 8.50,
                'service_category_id' => 7
            ],
            [
                'name' => "Tack (per gallon)",
                'cost' => 8.00,
                'service_category_id' => 8
            ],
            [
                'name' => "Paving Asphalt",
                'cost' => 96.00,
                'service_category_id' => 1
            ]
         );

        // @@todo add materials


        foreach($data as $datum) {
            $materials = new Material();
            $materials->name = $datum['name'];
            $materials->cost = $datum['cost'];
            $materials->service_category_id = $datum['service_category_id'];
            $materials->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
