<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuentasMatriculadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas_matriculadas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('owner_id')->unsigned(); 
            $table->bigInteger('third_id')->unsigned();
            $table->bigInteger('account_id')->unsigned();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');             
            $table->foreign('third_id')->references('id')->on('users')->onDelete('cascade');  
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuentas_matriculadas');
    }
}
