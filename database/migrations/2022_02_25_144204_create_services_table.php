<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('service_category_id')->default(0);
            $table->string('name', 100);
            $table->string('description', 255);
            $table->mediumText('service_template')->nullable();
            $table->mediumText('service_text_en')->nullable();
            $table->mediumText('service_text_es')->nullable();
            $table->string('old_service_cat', 100);
            $table->unsignedBigInteger('old_id')->default(0);
        });
        // @todo add services
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
