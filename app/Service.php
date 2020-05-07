<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
	const ACTIVE = 1;
    const REQUIRED = 2;
    const INACTIVE = 3;
    
    protected $fillable = [
    	'description',
    	'cost',
    	'state'
    ];

    protected $hidden = [
    	'remember_token'
    ];
}
