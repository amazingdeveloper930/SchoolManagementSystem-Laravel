<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    
    public function up(){
        Schema::create('contracts', function (Blueprint $table) {

            $table->increments('id');

            //EN CUANTO A LA MATRICULA
            $table->string('enrollment_grade');
            $table->string('enrollment_bachelor');
            $table->decimal('enrollment_cost', 8, 2);
            $table->text('receipt')->nullable();

            $table->decimal('paid_out', 8, 2)->default(0.00);

            $table->decimal('annuity_cost', 8, 2);
            $table->decimal('annuity_paid_out')->default(0.00);

            $table->unsignedInteger('year');

            $table->unsignedInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->string('request')->nullable();
            $table->date('date')->nullable();

            $table->text('observation')->nullable();

            //PRUEBA ========
            $table->decimal('r15_total', 8, 2)->default(0.00);
            $table->decimal('r1_total', 8, 2)->default(0.00);
            $table->decimal('r15_paid_out', 8, 2)->default(0.00);//CANTIDAD QUE QUEDA ABONADO PARA EL PAGO DE ALGUN R15
            $table->decimal('r1_paid_out', 8, 2)->default(0.00);//CANTIDAD QUE QUEDA ABONADO PARA EL APGO DE ALGUN R1

            
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('contracts');
    }
}
