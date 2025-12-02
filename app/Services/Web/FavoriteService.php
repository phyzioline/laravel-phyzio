<?php
namespace App\Services\Web;

use App\Models\Category;
use App\Models\Favorite;

class FavoriteService
{

    public function __construct(public Favorite $model){}

    public function index()
    {
        return $this->model->where('user_id',auth()->user()->id)->get();
    }

    public function store($data)
{
    
    $favorite = $this->model->where('user_id', auth()->id())
        ->where('product_id', $data['product_id'])
        ->first();

    if ($favorite) {
        $favorite->delete();
        return redirect()->back()->with('success', 'Product removed from favorites');
    }

    $this->model->create([
        'user_id' => auth()->id(),
        'product_id' => $data['product_id'],
        'favorite_type' => 1, 
    ]);

    return redirect()->back()->with('success', 'Product added to favorites');
}


    public function destroy($id)
    {
        $favorite = $this->model->findOrFail($id);

        if ($favorite) {
            $favorite->delete();
            return  $favorite;
        }
        return  $favorite;
    }



}

