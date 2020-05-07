<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'id',
    	'year',

        'enrollment_grade',
        'enrollment_bachelor',
        'enrollment_cost',
        'paid_out',

        'annuity_cost',
        'annuity_paid_out',
    	
        'student_id',
    	'user_id',
    	'request',

        'observation',

        //PRUEBA
        'r15_total',
        'r1_total',
        'r15_paid_out',
        'r1_paid_out'
    ];

    protected $hidden = [
    	'remember_token'
    ];

    protected $date = [
        'date',
    ];

    public function fees(){
    	return $this->hasMany(Fee::class)->orderBy('order','asc');
    }

    public function student(){
    	return $this->belongsTo(Student::class);
    }

    public function total(){
        return $this->hasOne(Total::class);
    }

    public function contract_services(){
        return $this->hasMany(Contract_Service::class);
    }

    /**
     * Get the user that owns the contract.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
