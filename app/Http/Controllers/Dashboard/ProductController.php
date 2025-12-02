<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\Tag;
use App\Models\Category;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\ProductService;
use App\Http\Requests\Dashboard\Product\StoreProductRequest;
use App\Http\Requests\Dashboard\Product\UpdateProductRequest;

class ProductController extends Controller
{

    public function __construct(public ProductService $productService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->productService->index();
        return view('dashboard.pages.product.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->productService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // dd($request->validated());
        $this->productService->store($request->validated());
        return redirect()->route('dashboard.products.index')->with('success','Created product');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productService->show($id);
        return view('dashboard.pages.product.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = $this->productService->show($id);
        $categories     = Category::where('status', 'active')->get();
        $sub_categories = SubCategory::where('status', 'active')->get();
        $tags           = Tag::where('status', 'active')->get();
        return view('dashboard.pages.product.edit',compact('product','categories', 'sub_categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $this->productService->update($id, $request->validated());
         return redirect()->route('dashboard.products.index')->with('success','Updated product');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->productService->destroy($id);
        return redirect()->route('dashboard.products.index')->with('success','Deleted product');

    }

}
