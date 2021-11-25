<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{	
    protected $guarded = ['id'];

    protected $table = 'enrollments';

    public $timestamps = false;
}

