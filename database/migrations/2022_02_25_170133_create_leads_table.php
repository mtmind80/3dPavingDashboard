<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('contact_type_id');
            $table->string('first_name', 255);
            $table->string('last_name', 80)->nullable();
            $table->string('email', 125)->nullable()->default(null);
            $table->string('phone', 25)->nullable();
            $table->string('address1', 110);
            $table->string('address2', 110)->nullable()->default(null);
            $table->string('city', 80)->nullable();
            $table->string('postal_code', 25)->nullable();
            $table->string('state', 80)->default('FL');
            $table->string('county', 80)->nullable();
            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('status_id')->index()->default(1);
            $table->unsignedBigInteger('assigned_to')->index()->nullable()->default(null);
            $table->boolean('worked_before')->nullable()->default(0);
            $table->string('worked_before_description', 255)->nullable()->default(null);
            $table->unsignedBigInteger('previous_assigned_to')->nullable()->default(null);
            $table->string('type_of_work_needed', 255)->nullable()->default(null);
            $table->string('lead_source', 125)->nullable()->default(null);
            $table->string('how_related', 125)->nullable()->default(null);
            $table->boolean('onsite')->default(0);
            $table->string('best_days', 255)->nullable()->default(null);
            $table->timestamps();            
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
