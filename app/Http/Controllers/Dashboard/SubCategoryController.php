<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Sub_Category\StoreSubCategoryRequest;
use App\Http\Requests\Dashboard\Sub_Category\UpdateSubCategoryRequest;
use App\Models\Category;
use App\Services\Dashboard\SubCategoryService;
use App\Traits\HasImage;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SubCategoryController extends Controller implements HasMiddleware
{
    use HasImage;

    public static function middleware(): array
    {
        return [
            new Middleware('can:sub_categories-index', only: ['index']),
            new Middleware('can:sub_categories-create', only: ['create', 'store']),
            new Middleware('can:sub_categories-show', only: ['show']),
            new Middleware('can:sub_categories-update', only: ['edit', 'update']),
            new Middleware('can:sub_categories-delete', only: ['destroy']),
        ];
    }

    public function __construct(public SubCategoryService $subCategoryService)
    {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->subCategoryService->index();
        return view('dashboard.pages.sub_category.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->subCategoryService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubCategoryRequest $request)
    {
        $this->subCategoryService->store($request->validated());
        return redirect()->route('dashboard.sub_categories.index')->with('success', 'Created Category');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->subCategoryService->show($id);
        return view('dashboard.pages.sub_category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sub_category = $this->subCategoryService->show($id);
        $categories = Category::all();
        return view('dashboard.pages.sub_category.edit', compact('sub_category','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubCategoryRequest $request, string $id)
    {
        $this->subCategoryService->update($request->validated(), $id);
        return redirect()->route('dashboard.sub_categories.index')->with('success', 'Updated Category');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->subCategoryService->destroy($id);
        return redirect()->route('dashboard.sub_categories.index')->with('success', 'Deleted Category');

    }

}
