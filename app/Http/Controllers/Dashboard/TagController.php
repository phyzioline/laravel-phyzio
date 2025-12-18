<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Tag;
use App\Traits\HasImage;
 use App\Http\Controllers\Controller;
use App\Services\Dashboard\TagService;
 use App\Http\Requests\Dashboard\Tag\StoreTagRequest;
 use App\Http\Requests\Dashboard\Tag\UpdateTagRequest;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TagController extends Controller implements HasMiddleware
{
    use HasImage;

    public static function middleware(): array
    {
        return [
            new Middleware('can:tags-index', only: ['index']),
            new Middleware('can:tags-create', only: ['create', 'store']),
            new Middleware('can:tags-show', only: ['show']),
            new Middleware('can:tags-update', only: ['edit', 'update']),
            new Middleware('can:tags-delete', only: ['destroy']),
        ];
    }

    public function __construct(public TagService $TagService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->TagService->index();
        return view('dashboard.pages.tag.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->TagService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $this->TagService->store($request->validated());
        return redirect()->route('dashboard.tags.index')->with('success','Created Tag');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tag = $this->TagService->show($id);
        return view('dashboard.pages.tag.show',compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tag = $this->TagService->show($id);
        return view('dashboard.pages.tag.edit',compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, string $id)
    {
        $this->TagService->update($request->validated(), $id);
         return redirect()->route('dashboard.tags.index')->with('success','Updated Tag');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->TagService->destroy($id);
        return redirect()->route('dashboard.tags.index')->with('success','Deleted Tag');

    }

}
