<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityPermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
        public function up()
    {
        Schema::table('permits', function($table) {
            $table->string('city', 80)->after('county')->nullable()->default(null);
            $table->date('expires_on')->after('number')->nullable()->default(null);
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permits', function($table) {
            $table->dropColumn('city');
            $table->dropColumn('expires_on');
        });
        
    }
}
