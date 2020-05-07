<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
    	'grade',
    	'bachelor',
    	'cost'
    ];

    protected $hidden = [
    	'remember_roken'
    ];
}
