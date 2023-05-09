<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlertReasonToProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
        public function up()
    {
        Schema::table('proposals', function($table) {
            $table->string('alert_reason', 225)->after('on_alert')->nullable()->default(null);
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposals', function($table) {
            $table->dropColumn('alert_reason');
        });
        
    }
}
