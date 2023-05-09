<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcceptedDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accepted_documents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('type', 50)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('extension', 50)->nullable();
            $table->integer('old_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accepted_documents');
    }
}
