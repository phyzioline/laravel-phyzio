<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use App\Traits\HasImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\CategoryService;
use App\Http\Requests\Dashboard\Category\CategoryRequest;
use App\Http\Requests\Dashboard\Category\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Category\CategoryUpdateRequest;
use App\Http\Requests\Dashboard\Category\UpdateCategoryRequest;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller implements HasMiddleware
{
    use HasImage;

    public static function middleware(): array
    {
        return [
            new Middleware('can:categories-index', only: ['index']),
            new Middleware('can:categories-create', only: ['create', 'store']),
            new Middleware('can:categories-show', only: ['show']),
            new Middleware('can:categories-update', only: ['edit', 'update']),
            new Middleware('can:categories-delete', only: ['destroy']),
        ];
    }

    public function __construct(public CategoryService $categoryService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->categoryService->index();
        return view('dashboard.pages.category.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->categoryService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->store($request->validated());
        return redirect()->route('dashboard.categories.index')->with('success','Created Category');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->categoryService->show($id);
        return view('dashboard.pages.category.show',compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = $this->categoryService->show($id);
        return view('dashboard.pages.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $this->categoryService->update($request->validated(), $id);
         return redirect()->route('dashboard.categories.index')->with('success','Updated Category');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->categoryService->destroy($id);
        return redirect()->route('dashboard.categories.index')->with('success','Deleted Category');

    }

}
