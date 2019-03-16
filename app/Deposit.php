<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deposits';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parment_method', 'start_date',
    ];

    /**
     * A deposit belongs to a user.
     * 
     * NOTE: This is prior to change!! 
     * 
     * Alter belongsTo function if necessary
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function schedule()
    {
        return $this->hasOne('App\Schedule');
    }
}
