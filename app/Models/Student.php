<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'mobile',
        'photo',
        'sign',
    ];
}
