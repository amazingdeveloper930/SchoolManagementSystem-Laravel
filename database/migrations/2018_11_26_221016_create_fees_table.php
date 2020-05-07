<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesTable extends Migration
{
    
    public function up()
    {
        Schema::create('fees', function (Blueprint $table){
            $table->increments('id');

            //RELACION CON UN CONTRATO
            $table->unsignedInteger('contract_id');
            $table->foreign('contract_id')->references('id')->on('contracts');
            
            //COSTO DE LA CUOTA
            $table->decimal('cost', 8, 2);            
            //LO QUE SE HA PAGADO DE LA CUOTA
            $table->decimal('paid_out', 8, 2)->default(0.00);

            //STATUS DE LA CUOTA
            $table->unsignedInteger('status')->default(App\Fee::ACTIVE);//cuota activa por defecto
            
            //ORDEN DE LA CUOTA (1-11)
            $table->unsignedInteger('order');

            //RECARGOS
            $table->decimal('r15', 8, 2)->default(0.00);
            $table->decimal('r1', 8, 2)->default(0.00);

            //MONTO YA CANCELADO DEL RECARGO
            $table->decimal('r15_paid_out', 8, 2)->default(0.00);
            $table->decimal('r1_paid_out', 8, 2)->default(0.00);

            //STATUS DE LOS RECARGOS
            $table->unsignedInteger('r15_status')->default(App\Fee::RECHARGE_INACTIVE);
            $table->unsignedInteger('r1_status')->default(App\Fee::RECHARGE_INACTIVE);     

            //COMENTARIO EN CASO DE CANCELAR CUOTA
            //$table->text('comment_cancel_r15')->nullable();
            //$table->text('comment_cancel_r1')->nullable();
            $table->text('r15_cancel_data')->nullable();
            $table->text('r1_cancel_data')->nullable();//AQUI VA NUMERO DE RECIBO, COMENTARIO DE CANCELACION, USUARIO, FECHA

            //INDICA SI LA CUOTA A EXPIRADO O NO
            $table->boolean('expired')->default(false);

            //FECHA DE VENCIMIENTO DE LA CUOTA
            $table->dateTime('date')->nullable();

            //JSON CON INFORMACION DE EL(LOS) RECIBOS
            $table->text('receipt')->nullable();


            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('fees');
    }
}
