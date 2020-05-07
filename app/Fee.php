<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Contract;

class Fee extends Model
{

    //STATUS
    const ACTIVE = 1;
    const PAY = 2;
    const INACTIVE = 0; //IGUAL A DECIR "CUOTA VENCIDA"

    const RECHARGE_INACTIVE = 0;
    const RECHARGE_ACTIVE = 1;
    const RECHARGE_PAY = 2;
    const RECHARGE_CANCEL = 3;//RECARGO ANULADO

    protected $fillable = [
        'id',
    	'contract_id',
    	'cost',
    	'status',
    	'order',
    	'r15',
        'r1',

        //PRUEBA
        'r15_paid_out',
        'r1_paid_out',
        //PRUEBA
        'r15_status',
        'r1_status',
        //prueba
        'paid_out',
        'expired',
        
        //prueba
        'r15_cancel_data',
        'r1_cancel_data',

        //oculto provicional
        //'comment_cancel_r15',
        //'comment_cancel_r1',
        
        //prueba
        'receipt'
    ];

    protected $hidden = [
    	'remember_token',
    ];

    protected $date = [
    	'date',
    ];

    /**
     * Relaciones
     */

    public function contract(){
        return $this->belongsTo(Contract::class);
    }

    /**
     * Funciones
     */
    public function surcharge()
    {
        
        $this->r15 = $this->cost * 0.15;
        $this->status = $this::INACTIVE;
        $this->contract->r15_total += $this->r15;
        $this->r15_status = Fee::RECHARGE_ACTIVE;
        $this->contract->update();
        $this->save();

    }

    public function r1($amount)
    {
        
        $this->r1 = $amount * 0.01;
        $this->contract->r1_total += $this->r1;
        $this->r1_status = Fee::RECHARGE_ACTIVE;
        $this->contract->update(); 
        $this->save();

    }

}
