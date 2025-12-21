<?php
namespace App\Services\Dashboard;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Tag;

class ProductService
{

    public function __construct(public Product $model)
    {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // For admin users, show all products. For vendors, show only their products.
        $query = $this->model->with(['category', 'sub_category', 'productImages']);
        
        if (auth()->user()->hasRole('admin')) {
            // Admin sees all products - no filter needed
            return $query->get();
        } else {
            // Vendor sees only their products
            return $query->where('user_id', auth()->user()->id)->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories     = Category::where('status', 'active')->get();
        $sub_categories = SubCategory::where('status', 'active')->get();
        $tags           = Tag::where('status', 'active')->get();
        return view('dashboard.pages.product.create', compact('categories', 'sub_categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($data)
    {
        $data['user_id'] = auth()->user()->id;
        $product         = $this->model->create(\Illuminate\Support\Arr::except($data, ['images', 'tags']));

        if (! empty($data['images']) && is_array($data['images'])) {
            $imagesData = [];

            foreach ($data['images'] as $image) {
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/products'), $fileName);

                $imagesData[] = ['image' => 'uploads/products/' . $fileName];
            }

            $product->productImages()->createMany($imagesData);
        }

        if (! empty($data['tags']) && is_array($data['tags'])) {
            $product->tags()->attach($data['tags']);
        }

        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        return $this->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, $data)
    {
        $product = $this->model->findOrFail($id);

        // Exclude 'tags' and 'images' from direct update - they're handled separately
        $product->update(\Illuminate\Support\Arr::except($data, ['images', 'tags']));

        if (! empty($data['images']) && is_array($data['images'])) {
            $product->productImages()->delete();

             $imagesData = [];

            foreach ($data['images'] as $image) {
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/products'), $fileName);

                $imagesData[] = ['image' => 'uploads/products/' . $fileName];
            }

            $product->productImages()->createMany($imagesData);
        }

        // Handle tags separately using the relationship
        if (isset($data['tags'])) {
            if (! empty($data['tags']) && is_array($data['tags'])) {
                $product->tags()->sync($data['tags']);
            } else {
                // If tags array is empty, remove all tags
                $product->tags()->sync([]);
            }
        }

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = $this->show($id);
        return $product->delete();
    }
}
