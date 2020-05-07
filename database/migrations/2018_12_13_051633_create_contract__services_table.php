<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


//TABLA UTILIZADA PARA AGREGAR LOS SERVICIOS OBLIGATORIOS ASOCIADOS A UN CONTRATO

class CreateContractServicesTable extends Migration
{
    public function up()
    {
        Schema::create('contract__services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->decimal('cost', 8, 2);
            $table->text('receipt')->nullable();

            $table->decimal('paid_out', 8, 2)->default(0.00);
            
            $table->unsignedInteger('contract_id');
            $table->foreign('contract_id')->references('id')->on('contracts');

            $table->unsignedInteger('state');
            
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('contract__services');
    }
}
