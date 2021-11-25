<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DtrLog extends Model
{	
    protected $guarded = ['id'];

    protected $table = 'dtr_logs';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}

