<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class JobController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:jobs-index', only: ['index']),
            new Middleware('can:jobs-create', only: ['create', 'store']),
            new Middleware('can:jobs-show', only: ['show']),
            new Middleware('can:jobs-update', only: ['edit', 'update']),
            new Middleware('can:jobs-delete', only: ['destroy']),
        ];
    }


    public function index(Request $request)
    {
        // Use 'clinic_jobs' table logic
        $data = Job::with('clinic')->latest()->paginate(10);
        return view('dashboard.jobs.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }
}
