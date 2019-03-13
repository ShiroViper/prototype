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
        return $this->belongsTo('App\User');
    }

<<<<<<< HEAD
=======
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

>>>>>>> 2f5d8637b7c6e00af732136696898873820fae9d
    /**
     * Get the Schedule of the Loan Request.
     */
    public function schedule()
    {
        return $this->hasOne('App\Schedule');
    }
}
