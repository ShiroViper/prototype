<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan_Request extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loan_request';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the User that owns the Loan Request.
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    /**
     * Get the Schedule of the Loan Request.
     */
    public function schedule()
    {
        return $this->hasOne('App\Schedule');
    }
}
