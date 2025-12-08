<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataHubController extends Controller
{
    public function index()
    {
        return view('web.pages.datahub.index');
    }

    public function dashboard()
    {
        return view('web.pages.datahub.dashboard');
    }

    public function licensing()
    {
        return view('web.pages.datahub.licensing');
    }
}
