<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{	
    protected $guarded = ['id'];

    protected $table = 'calendars';

    public $timestamps = false;
}

