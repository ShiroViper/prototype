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
}
