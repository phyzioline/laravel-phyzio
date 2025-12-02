<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\PrivacyPolicy;
use App\Http\Controllers\Controller;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        $privacy_policy = PrivacyPolicy::first();
        return view('web.pages.privacy-policy' , compact('privacy_policy'));
    }


}
