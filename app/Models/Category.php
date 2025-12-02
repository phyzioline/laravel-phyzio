<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'status'
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }



}
