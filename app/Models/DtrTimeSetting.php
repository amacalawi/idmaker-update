<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DtrTimeSetting extends Model
{	
    protected $guarded = ['id'];

    protected $table = 'dtr_time_settings';

    public $timestamps = false;
}

