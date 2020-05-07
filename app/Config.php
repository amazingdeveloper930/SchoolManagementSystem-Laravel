<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = [
    	'id',
    	'type',
    	'content'
    ];

    protected $hidden = [
    	'remember_token'
    ];
}
