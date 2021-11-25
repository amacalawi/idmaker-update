<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{	
    protected $guarded = ['id'];

    protected $table = 'batches';

    public $timestamps = false;
}

