<?php
namespace App\Models;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'sub_category_id',
        'product_name_ar',
        'product_name_en',
        'product_price',
        'short_description_ar',
        'short_description_en',
        'long_description_ar',
        'long_description_en',
        'amount',
        'sku',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    public function getStatusAttribute($value)
    {
        return $value === 'active' ? 'Active' : 'Inactive';
    }
    public function favorite()
{
    return $this->hasOne(Favorite::class)->where('user_id', auth()->id());
}

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $latestId     = Product::max('id') + 1;
            $product->sku = 'HO' . str_pad($latestId, 3, '0', STR_PAD_LEFT);
        });
    }

    public function getImageUrlAttribute()
    {
        return $this->productImages->first() ? asset('storage/' . $this->productImages->first()->image) : null;
    }

}
