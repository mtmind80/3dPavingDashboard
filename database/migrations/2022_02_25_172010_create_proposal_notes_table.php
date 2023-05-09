<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_notes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->boolean('reminder')->nullable()->default(0);
            $table->boolean('remindersent')->nullable()->default(0);
            $table->date('reminder_date')->nullable()->default(null);
            $table->unsignedBigInteger('proposal_id')->index();
            $table->unsignedBigInteger('created_by');
            $table->mediumText('note');
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
        Schema::dropIfExists('proposal_notes');
    }
}
