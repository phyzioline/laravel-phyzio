<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public $table= 'favorites'; 
    protected $fillable = [
        'user_id',
        'product_id',
        'favorite_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    protected function casts(): array
    {
        return [
            'product_id' => 'int',
            'user_id' => 'int',
        ];
    }

}
