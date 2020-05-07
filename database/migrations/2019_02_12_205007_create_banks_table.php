<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanksTable extends Migration{

    public function up()
    {
        Schema::create('banks', function (Blueprint $table){
            $table->increments('id');
            $table->decimal('amount',8,2);
            $table->dateTime('date');
            $table->string('user');
            $table->integer('year');
            $table->integer('month');
            $table->timestamps();
        });
    }

    

    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
