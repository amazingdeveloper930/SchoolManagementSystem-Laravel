<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Total extends Model
{
    protected $fillable = [
    	'balance',
    	'contract_id'
    ];

    protected $hidden = [
    	'remember_token'
    ];

    public function contract(){
    	return $this->belongsTo(Contract::class);
    }
}
