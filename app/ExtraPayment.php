<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtraPayment extends Model
{
    
    protected $fillable = [
        'id',
    	'description',
    	'cost',
    	'state',
    	'year',
    	'student_id',
        'service_id',
        'receipt',

        //prueba
        'paid_out'
    ];

    protected $hidden = [
    	'remember_token'
    ];

    public function student(){
    	return $this->belongsTo(Student::class);
    }
}
