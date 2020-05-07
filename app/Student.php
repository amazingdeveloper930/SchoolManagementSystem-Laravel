<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
	const INACTIVE = 0;
  const ACTIVE = 1;
  const SUSPENDED = 2;
  const RETIRED = 4;
  
	const SALVE = 1;

    protected $fillable = [
      'personal_id',
    	'name',
      'email',
      'phone',
    	'attendant',
    	'peace_save',
    	'status',
    ];

   	protected $hidden = [
   		'remember_token'
   	];


    //METODOS
    public function contracts(){
      return $this->hasMany(Contract::class);
    }

    public function payments(){
      return $this->belongsToMany(Payment::class);
    }

    public function suspended()
    {
      $this->status = $this::SUSPENDED;
      $this->save();
    }

    public function actived(){
      $this->status = $this::ACTIVE;
      $this->save();
    }

    public function inactived(){
      $this->status = $this::INACTIVE;
      $this->save();
    }

    public function extraPayments(){
      return $this->hasMany(ExtraPayment::class);
    }
}
