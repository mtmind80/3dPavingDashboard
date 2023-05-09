<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherdescriptionToProposalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
        public function up()
    {
        Schema::table('proposal_details', function($table) {
            $table->mediumText('alt_desc')->after('service_desc')->nullable();
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposal_details', function($table) {
            $table->dropColumn('alt_desc');
        });
        
    }
}
