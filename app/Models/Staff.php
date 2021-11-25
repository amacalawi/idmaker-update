<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{	
    protected $guarded = ['id'];

    protected $table = 'staffs';

    public $timestamps = false;
}

