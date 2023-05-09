<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ContactType;

class CreateContactTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_types', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('type', 50);
            $table->string('description', 250);
        });

        $data = array(
            [
                'id' => 1,
                'type' => 'National Company',
                'description' => 'National Company with multiple offices across the country.',
            ],
            [
                'id' => 7,
                'type' => 'Management Company',
                'description' => 'Local or National Property Management Company.',
            ],
            [
                'id' => 10,
                'type' => 'Government Entity',
                'description' => 'Any Government, City, County, State, Federal or Military entity.',
            ],
            [
                'id' => 14,
                'type' => 'POA/COA/HOA',
                'description' => "Property Owner's Association, Condo Owner's Association, Home Owner's Association",
            ],
            [
                'id' => 16,
                'type' => 'Property Owner',
                'description' => 'Property Owner - Residential',
            ],
            [
                'id' => 18,
                'type' => 'General Contact',
                'description' => "General Contacts",
            ],
            [
                'id' => 22,
                'type' => 'Contractor',
                'description' => "General Contractors, National Contractors, Specialty Contractors",
            ],
        );
        foreach($data as $datum) {
            $record = new ContactType();
            $record->id = $datum['id'];
            $record->type = $datum['type'];
            $record->description = $datum['description'];
            $record->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_types');
    }
}
