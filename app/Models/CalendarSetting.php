<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalendarSetting extends Model
{	
    protected $guarded = ['id'];

    protected $table = 'calendars_settings';

    public $timestamps = false;
}

