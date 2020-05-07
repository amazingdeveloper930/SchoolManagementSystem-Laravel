<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //public static $NUM_RECEIPT = 0;
    public const AHC_DEPOSIT = 1;
    public const SLIP_BANK = 2;

    protected $fillable = [
    	'receipt',
    	'attendant',
    	'operation_number',
    	'amount',
        'refund',
        'refund_comment',
        'info_str',
        'status',
        'cancel_info',
        //'attendant',
        'user',
        'pay_method'
    ];

    protected $hidden = [
    	'remember_token',
    ];

    protected $date = [
    	'deposit_at',
    ];

    public function students(){
    	return $this->belongsToMany(Student::class);
    }

    /*public function increment_receipt(){
        $NUM_RECEIPT++;

        return $NUM_RECEIPT;
    }*/
}
