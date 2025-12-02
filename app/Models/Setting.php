<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
     protected $fillable = [
        'image',
        'description_ar',
        'description_en',
        'address_ar',
        'address_en',
        'email',
        'phone',
        'facebook',
        'twitter',
        'instagram',
    ];
}
