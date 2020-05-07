<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    
    public function up(){
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->decimal('cost', 8, 2);
            $table->integer('state')->default(\App\Service::ACTIVE);
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
