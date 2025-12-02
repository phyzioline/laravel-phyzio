<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\TearmsCondition;
use App\Http\Controllers\Controller;

class TearmsConditionController extends Controller
{
    public function index()
    {
        $tearms_condition = TearmsCondition::first();
        return view('web.pages.tearms-condition' , compact('tearms_condition'));
    }


}
