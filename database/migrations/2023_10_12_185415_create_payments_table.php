<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->enum('payment_type',['Deposit','Interim Payment','Additional Payment','Final Payment']);
            $table->float('payment',8,2, true);
            $table->string('check_no',25)->nullable();
            $table->string('note',1000)->nullable();
            $table->string('cert_holder',50)->nullable();
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('payments');
    }
}
