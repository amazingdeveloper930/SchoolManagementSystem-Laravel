<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
    	'amount',
    	'user',
        'month',
        'year'
    ];

    protected $hidden = [
    	'remember_token',
    ];

    protected $date = [
    	'date',
    ];
}
