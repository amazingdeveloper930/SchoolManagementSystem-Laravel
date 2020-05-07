<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

//TABLA NECESARIA PARA AGREGAR LOS SERVICIOS NO OBLIGATORIOS ASOCIADOS A UN ESTUDIANTE
class CreateExtraPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('extra_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->decimal('cost', 8, 2);
            $table->text('receipt')->nullable();
            $table->integer('state')->default(\App\Service::ACTIVE);

            $table->decimal('paid_out', 8, 2)->default(0.00);
            
            $table->unsignedInteger('year');
            $table->unsignedInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');

            //SOLO POR REFERENCIA, NO TIENE QUE VER CON UNA RELACION
            $table->unsignedInteger('service_id');

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
        Schema::dropIfExists('extra_payments');
    }
}
