<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    const MALE = 1;
    const FEMALE = 2;

    protected $fillable = [
        'title',
    ];

    public $timestamps = false;
}
