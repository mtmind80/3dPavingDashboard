<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadSourcesTable extends Migration
{
    public function up()
    {
        Schema::create('lead_sources', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 125)->unique();
        });

        DB::table('lead_sources')->insert([
            ['name' => 'Internet/Search'],
            ['name' => 'Referral'],
            ['name' => 'Mailer/Postcard'],
            ['name' => 'Previous Customer'],
            ['name' => 'Website'],
            ['name' => 'Other'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('lead_sources');
    }
}
