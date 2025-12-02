<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
     protected $fillable = [
        'category_id',
        'name_ar',
        'name_en',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    
    public function products()
{
    return $this->hasMany(Product::class);
}

}
