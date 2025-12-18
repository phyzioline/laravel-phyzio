<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DataPointController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:data_points-index', only: ['index']),
            new Middleware('can:data_points-create', only: ['create', 'store']),
            new Middleware('can:data_points-show', only: ['show']),
            new Middleware('can:data_points-update', only: ['edit', 'update']),
            new Middleware('can:data_points-delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data_points = \App\Models\DataPoint::all();
        return view('dashboard.data_points.index', compact('data_points'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.data_points.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:255',
            'total_therapists' => 'required|integer',
            'average_salary' => 'required|numeric',
            'employment_rate' => 'nullable|numeric',
            'year' => 'required|integer',
        ]);

        \App\Models\DataPoint::create($request->all());
        return redirect()->route('dashboard.data_points.index')->with('message', ['type' => 'success', 'text' => 'Data Point created successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data_point = \App\Models\DataPoint::findOrFail($id);
        return view('dashboard.data_points.edit', compact('data_point'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data_point = \App\Models\DataPoint::findOrFail($id);
        $data_point->update($request->all());
        return redirect()->route('dashboard.data_points.index')->with('message', ['type' => 'success', 'text' => 'Data Point updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
