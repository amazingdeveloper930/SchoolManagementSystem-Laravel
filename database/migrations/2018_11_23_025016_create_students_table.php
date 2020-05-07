<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('personal_id')->unique();
            $table->string('attendant');
            $table->boolean('peace_save')->default(false);//Estudiante paz y salvo por defecto
            $table->integer('status')->default(\App\Student::INACTIVE);//Estudiante inactivo por defecto
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    
    
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
