<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DtrLogSubject extends Model
{	
    protected $guarded = ['id'];

    protected $table = 'dtr_log_subject';

    public $timestamps = false;

    public function member()
    {
        return $this->belongsTo('App\Member', 'memder_id', 'id');
    }
}

