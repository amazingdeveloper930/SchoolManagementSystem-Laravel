<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnuitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annuities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('year')->nullable();
            $table->decimal('cost', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->date('maximum_date');
            $table->unsignedInteger('second_month');
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
        Schema::dropIfExists('annuities');
    }
}
