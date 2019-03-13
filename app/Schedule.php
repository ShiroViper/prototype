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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'transaction_id', 'start_date', 'end_date', 'created_at', 'updated_at'
    // ];

    /**
     * A Schedule has many Loan Request.
     * 
     * NOTE: This is prior to change!! 
     * 
     * Alter belongsTo function if necessary
     */
    public function loanRequest()
    {
        return $this->belongsTo('App\Loan_Request');
    }

    public function transaction() 
    {
        return $this->belongsTo('App\Transaction');
    }

}
