<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['user_type'];

    /**
     * A User may have a lot of loan requests.
     * 
     * Alter if necessary
     */
    public function loanRequests()
    {
        return $this->hasMany('App\Loan_Request');
    }

    public function schedule()
    {
        return $this->hasMany('App\Schedule');
    }
    
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function deposit()
    {
        return $this->hasOne('App\Deposit');
    }
    
    public function comment()
    {
        return $this->hasOne('App\Comment');
    }
}
