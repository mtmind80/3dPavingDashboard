<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contractors', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 100);
            $table->string('contact', 50)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('address_line1', 100)->nullable();
            $table->string('address_line2', 100)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('state', 80)->default('FL');
            $table->string('postal_code', 25)->nullable();
            $table->string('email', 125)->nullable();
            $table->mediumText('note')->nullable();
            $table->tinyInteger('disable')->default(0);
            $table->unsignedBigInteger('contractor_type_id')->default(1);
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
        Schema::dropIfExists('contractors');
    }
}
