<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'schedules';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * A Schedule has many Loan Request.
     * 
     * NOTE: This is prior to change!! 
     * 
     * Alter belongsTo function if necessary
     */
    public function loanRequest()
    {
        return $this->belongsTo('App\Loan_Request', 'id');
    }

    public function deposit()
    {
        return $this->belongsTo('App\Deposit', 'id');
    }

}
