<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user_redirect()
    {
        
         return redirect()->route('balance.index');
        /*if ($this->hasRole('super_admin')) {
            return redirect()->route('users');
        } else {
            if ($this->can('students.index')) {
                return redirect()->route('student.index');
            }
            if ($this->can('payments.index')) {
                return redirect()->route('payment.index');
            }
            if ($this->can('costs.index')) {
                return redirect()->route('service.index');
            }
            if ($this->can('reports.index')) {
                return redirect()->route('reports');
            }
        }*/

    }

    public function contracts(){
        return $this->hasMany(Contract::class);
    }

}
