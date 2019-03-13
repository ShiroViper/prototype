<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // table
    protected $table="transactions";

    // Timestamps
    public $timestamps = true;

    // Primary key
    public $primaryKey = 'member_id';

    public function loanRequests(){
       return  $this->belongsTo('App\Loan_Request');
    }
    public function user(){
       return  $this->belongsTo('App\User');
    }
}

