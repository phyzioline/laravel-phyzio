<?php

namespace App\Http\Controllers\Web;

use GuzzleHttp\Psr7\Request;
use App\Http\Controllers\Controller;
use App\Services\Web\FavoriteService;
use App\Http\Requests\Web\FavoriteRequest;

class FavoriteController extends Controller
{
    public function __construct(public FavoriteService $favoriteService)
    {}

    public function index()
    {
        $data = $this->favoriteService->index();
        return view('web.pages.favorites', compact('data'));
    }

    public function store(FavoriteRequest $request)
    {
        return $this->favoriteService->store($request->validated());
    }

    public function destroy($id)
    {
        $this->favoriteService->destroy($id);
        return to_route('favorites.index')->with('success', 'Product removed from favorites successfully.');
    }

}
