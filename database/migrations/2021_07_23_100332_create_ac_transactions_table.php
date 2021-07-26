<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_id')->unsigned();
            $table->bigInteger('transaction_id')->unsigned();
            $table->integer('amount')->unsigned()->default(0);
            $table->integer('type')->default(1);                
            /*
            $table->foreign('transaction_id')
            ->references('id')->on('transactions')
            ->onDelete('cascade');     */        
                              
            $table->foreign('account_id')
            ->references('id')->on('accounts')
            ->onDelete('cascade');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_transactions');
    }
}
