<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permits', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_id')->index();
            $table->unsignedBigInteger('proposal_detail_id')->default(null)->nullable();
            $table->enum('status', ['Approved','Not Submitted', 'Submitted', 'Under Review','Comments','Completed'])->default('Not Submitted');
            $table->enum('type', ['Building', 'Engineering', 'Miscellaneous'])->default('Building')->nullable();
            $table->string('number', 25)->default(null)->nullable();
            $table->string('county', 100)->default(null)->nullable();
            $table->string('cert_holder',50)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('last_updated_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permits');
    }
}
