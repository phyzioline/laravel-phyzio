<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPoint extends Model
{
    protected $fillable = [
        'country',
        'category',
        'metric',
        'value',
        'year',
        'source'
    ];
}
