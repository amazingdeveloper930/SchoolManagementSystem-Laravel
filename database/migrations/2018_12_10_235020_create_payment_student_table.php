<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentStudentTable extends Migration
{
    
    public function up()
    {
        Schema::create('payment_student', function (Blueprint $table){
            $table->unsignedInteger('payment_id');
            $table->unsignedInteger('student_id');
        });
    }



    public function down()
    {
        Schema::dropIfExists('payment_student');
    }
}
