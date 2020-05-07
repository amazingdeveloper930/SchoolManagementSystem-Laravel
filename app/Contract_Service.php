<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract_Service extends Model
{
    
    const ACTIVE = 1;
    const REQUIRED = 2;

    protected $fillable = [
        'id',
    	'description',
    	'cost',
    	'contract_id',
        'state',
        'paid_out'
    ];


    protected $hidden = [
    	'remember_token'
    ];

    
    public function contract(){
    	return $this->belongsTo(Contract::class);
    }
}
