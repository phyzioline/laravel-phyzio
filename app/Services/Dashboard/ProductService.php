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
    public function index($sortBy = null)
    {
        // For admin users, show all products. For vendors, show only their products.
        $baseQuery = $this->model->with(['category', 'sub_category', 'productImages', 'metrics']);
        
        if (!auth()->user()->hasRole('admin')) {
            // Vendor sees only their products
            $baseQuery->where('user_id', auth()->user()->id);
        }
        
        // Apply sorting - this may need joins, so we'll handle it in applySorting
        $query = $this->applySorting($baseQuery, $sortBy);
        
        return $query->get();
    }
    
    /**
     * Apply sorting to the query based on sort parameter
     */
    private function applySorting($query, $sortBy)
    {
        switch ($sortBy) {
            case 'sales_highest':
                $query->leftJoin('product_metrics', 'products.id', '=', 'product_metrics.product_id')
                    ->select('products.*')
                    ->orderByRaw('COALESCE(product_metrics.revenue, 0) DESC');
                break;
            case 'sales_lowest':
                $query->leftJoin('product_metrics', 'products.id', '=', 'product_metrics.product_id')
                    ->select('products.*')
                    ->orderByRaw('COALESCE(product_metrics.revenue, 0) ASC');
                break;
            case 'units_sold_highest':
                $query->orderByRaw('(SELECT COALESCE(SUM(quantity), 0) FROM items_orders WHERE items_orders.product_id = products.id) DESC');
                break;
            case 'units_sold_lowest':
                $query->orderByRaw('(SELECT COALESCE(SUM(quantity), 0) FROM items_orders WHERE items_orders.product_id = products.id) ASC');
                break;
            case 'status_asc':
                $query->orderBy('status', 'ASC');
                break;
            case 'status_desc':
                $query->orderBy('status', 'DESC');
                break;
            case 'sku_az':
                $query->orderBy('sku', 'ASC');
                break;
            case 'sku_za':
                $query->orderBy('sku', 'DESC');
                break;
            case 'title_az':
                $query->orderBy('product_name_en', 'ASC');
                break;
            case 'title_za':
                $query->orderBy('product_name_en', 'DESC');
                break;
            case 'price_high_low':
                $query->orderByRaw('CAST(product_price AS DECIMAL(10,2)) DESC');
                break;
            case 'price_low_high':
                $query->orderByRaw('CAST(product_price AS DECIMAL(10,2)) ASC');
                break;
            case 'date_new_old':
                $query->orderBy('created_at', 'DESC');
                break;
            case 'date_old_new':
                $query->orderBy('created_at', 'ASC');
                break;
            case 'updated_last':
                $query->orderBy('updated_at', 'DESC');
                break;
            case 'available_high_low':
                $query->orderBy('amount', 'DESC');
                break;
            case 'available_low_high':
                $query->orderBy('amount', 'ASC');
                break;
            case 'page_views_highest':
                $query->leftJoin('product_metrics', 'products.id', '=', 'product_metrics.product_id')
                    ->select('products.*')
                    ->orderByRaw('COALESCE(product_metrics.views, 0) DESC');
                break;
            case 'page_views_lowest':
                $query->leftJoin('product_metrics', 'products.id', '=', 'product_metrics.product_id')
                    ->select('products.*')
                    ->orderByRaw('COALESCE(product_metrics.views, 0) ASC');
                break;
            case 'inbound_high_low':
            case 'inbound_low_high':
            case 'reserved_high_low':
            case 'reserved_low_high':
            case 'unfulfillable_high_low':
            case 'unfulfillable_low_high':
                // These would require additional inventory tracking fields
                // For now, sort by amount as placeholder
                $direction = strpos($sortBy, 'high_low') !== false ? 'DESC' : 'ASC';
                $query->orderBy('amount', $direction);
                break;
            default:
                // Default: Sort by ID descending (newest first)
                $query->orderBy('id', 'DESC');
                break;
        }
        
        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories     = Category::where('status', 'active')->get();
        $sub_categories = SubCategory::where('status', 'active')->get();
        $tags           = Tag::where('status', 'active')->get();
        
        // Check if copying from existing product
        $copyFrom = request('copy_from');
        $sourceProduct = null;
        if ($copyFrom) {
            $sourceProduct = $this->model->find($copyFrom);
        }
        
        return view('dashboard.pages.product.create-enhanced', compact('categories', 'sub_categories', 'tags', 'sourceProduct'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($data)
    {
        $data['user_id'] = auth()->user()->id;
        
        // Handle action (draft vs publish)
        if (isset($data['action']) && $data['action'] === 'draft') {
            $data['status'] = 'inactive';
        } elseif (isset($data['action']) && $data['action'] === 'publish') {
            $data['status'] = 'active';
        }
        
        // Handle variation attributes as JSON
        if (isset($data['variation_attributes']) && is_array($data['variation_attributes'])) {
            $data['variation_attributes'] = json_encode($data['variation_attributes']);
        }
        
        $product = $this->model->create(\Illuminate\Support\Arr::except($data, ['images', 'tags', 'action']));

        if (! empty($data['images']) && is_array($data['images'])) {
            $imagesData = [];

            foreach ($data['images'] as $image) {
                if ($image && $image->isValid()) {
                    $fileName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/products'), $fileName);

                    $imagesData[] = ['image' => 'uploads/products/' . $fileName];
                }
            }
            
            if (!empty($imagesData)) {
                $product->productImages()->createMany($imagesData);
            }
        }

        if (! empty($data['tags']) && is_array($data['tags'])) {
            $product->tags()->attach($data['tags']);
        }

        // Dispatch Feed Event
        \App\Events\ProductCreated::dispatch($product);

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

        // Handle action (draft vs publish)
        if (isset($data['action']) && $data['action'] === 'draft') {
            $data['status'] = 'inactive';
        } elseif (isset($data['action']) && $data['action'] === 'publish') {
            $data['status'] = 'active';
        }

        // Handle variation attributes as JSON
        if (isset($data['variation_attributes']) && is_array($data['variation_attributes'])) {
            $data['variation_attributes'] = json_encode($data['variation_attributes']);
        }

        // Handle image removal
        if (isset($data['remove_images']) && !empty($data['remove_images'])) {
            $imageIds = explode(',', $data['remove_images']);
            foreach ($imageIds as $imageId) {
                $image = \App\Models\ProductImage::find($imageId);
                if ($image && $image->product_id == $product->id) {
                    // Delete file if exists
                    if (file_exists(public_path($image->image))) {
                        @unlink(public_path($image->image));
                    }
                    $image->delete();
                }
            }
        }

        // Exclude 'tags', 'images', 'action', and 'remove_images' from direct update
        $product->update(\Illuminate\Support\Arr::except($data, ['images', 'tags', 'action', 'remove_images']));

        // Handle new images upload
        if (! empty($data['images']) && is_array($data['images'])) {
            $imagesData = [];

            foreach ($data['images'] as $image) {
                if ($image && $image->isValid()) {
                    $fileName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/products'), $fileName);

                    $imagesData[] = ['image' => 'uploads/products/' . $fileName];
                }
            }
            
            if (!empty($imagesData)) {
                $product->productImages()->createMany($imagesData);
            }
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
