<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('contact_type_id');
            $table->unsignedBigInteger('related_to')->default(null)->nullable();
            $table->unsignedBigInteger('lead_id')->nullable()->default(null);
            $table->string('first_name', 255);
            $table->string('last_name', 80)->nullable();
            $table->string('email', 125)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('alt_email', 125)->nullable();
            $table->string('alt_phone', 25)->nullable();
            $table->string('address1', 110);
            $table->string('address2', 110)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('postal_code', 25)->nullable();
            $table->string('state', 80)->default('FL');
            $table->string('county', 80)->nullable();
            $table->string('billing_address1', 225)->nullable();
            $table->string('billing_address2', 225)->nullable();
            $table->string('billing_city', 80)->nullable();
            $table->string('billing_postal_code', 10)->nullable();
            $table->string('billing_state', 80)->default('FL')->nullable();
            $table->string('contact', 80)->nullable();
            $table->mediumText('note')->nullable();
            $table->unsignedBigInteger('created_by')->default(null)->nullable();
            $table->unsignedBigInteger('old_id')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
