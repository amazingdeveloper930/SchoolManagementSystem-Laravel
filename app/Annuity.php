<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Annuity extends Model
{
    
    protected $fillable = [
        'year',
    	'cost',
    	'discount',
        'second_month',
        'maximum_date',
    ];

    protected $hidden = [
    	'remember_token'
    ];

    // protected $date = [
    // 	'maximum_date',
    // ];
}
