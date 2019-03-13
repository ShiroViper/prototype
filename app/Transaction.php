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
    // public $primaryKey = 'id';

    // protected $fillable = [
    //     'created_at', 'updated_at'
    // ];

    public function schedules() 
    {
        return $this->hasOne('App\Schedule');
    }
}
