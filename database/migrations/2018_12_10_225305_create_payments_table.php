<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->date('deposit_at');
            $table->string('receipt');
            
            $table->string('attendant');
            $table->string('operation_number')->unique();
            $table->decimal('amount', 8, 2);
            $table->string('user');

            $table->decimal('refund', 8, 2);
            //$table->string('refund_comment')->nullable();

            $table->boolean('status')->default(true);

            $table->text('info_str');
            $table->text('cancel_info')->nullable();
            $table->timestamps();
        });
    }

    

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
