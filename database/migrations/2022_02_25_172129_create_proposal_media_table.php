<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_media', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedBigInteger('proposal_detail_id')->nullable();
            $table->integer('media_type_id')->default(1);
            $table->mediumText('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('file_name',255)->nullable();
            $table->string('file_type',80)->nullable();
            $table->string('file_path',80)->nullable();
            $table->string('original_name',255)->nullable();
            $table->string('file_ext',80)->nullable();
            $table->string('file_size',80)->nullable();
            $table->boolean('IsImage')->default(0);
            $table->string('image_height',50)->nullable();
            $table->string('image_width',50)->nullable();
            $table->boolean('admin_only')->default(0);

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
        Schema::dropIfExists('proposal_media');
    }
}
