<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebConfigBaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_config_bak', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('key','50')->index('pkdex_key')->unique();
            $table->string('value','255');
            $table->string('description','255')->nullable()->default('');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_config_bak');
    }
}
