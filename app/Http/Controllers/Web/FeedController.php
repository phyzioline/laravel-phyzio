<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Log;

class FeedController extends Controller
{
    public function google($lang = 'en')
    {
        if (! in_array($lang, ['en', 'ar'])) {
            abort(404);
        }

        $products = Cache::remember("google_feed_{$lang}", 600, function () use ($lang) {
            return Product::where('status', 'active')->with('productImages', 'vendor')->get();
        });

        // Prepare localized data in the view
        return response()
            ->view('feeds.google', compact('products', 'lang'))
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=600');
    }
}
