<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DaysOff extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dayOfWeek' => 'array',
    ];
}
