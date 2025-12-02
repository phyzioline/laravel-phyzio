<?php
namespace App\Services\Dashboard;

use App\Models\Category;

class CategoryService
{

    public function __construct(public Category $model){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->model->paginate();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.pages.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($data)
    {
        return $this->model->create($data);
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
    public function update($data, string $id)
    {
        $category = $this->show($id);
        return $category->update($data);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = $this->show($id);
        return $category->delete();
    }
}
