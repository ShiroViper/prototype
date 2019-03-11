<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collector extends Model
{
    // table
    protected $table="transactions";

    // Timestamps
    public $timestamps = true;
}
