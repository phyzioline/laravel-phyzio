<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PricingController extends Controller
{
    /**
     * Display pricing management page
     */
    public function manage(Request $request)
    {
        $query = Product::with(['category', 'productImages']);

        if (!auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('product_name_en', 'like', "%{$search}%")
                  ->orWhere('product_name_ar', 'like', "%{$search}%");
            });
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('product_price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('product_price', '<=', $request->max_price);
        }

        $products = $query->paginate(50);

        $statsQuery = Product::query();
        if (!auth()->user()->hasRole('admin')) {
            $statsQuery->where('user_id', auth()->id());
        }

        $stats = [
            'total_products' => (clone $statsQuery)->count(),
            'avg_price' => round((clone $statsQuery)->avg('product_price'), 2),
            'highest_price' => (clone $statsQuery)->max('product_price'),
            'lowest_price' => (clone $statsQuery)->min('product_price'),
        ];

        return view('dashboard.pages.pricing.manage', compact('products', 'stats'));
    }

    /**
     * Display price rules page
     */
    public function rules()
    {
        // Placeholder for price rules feature
        return view('dashboard.pages.pricing.rules');
    }

    /**
     * Update product price
     */
    public function updatePrice(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if (!auth()->user()->hasRole('admin') && $product->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $product->update(['product_price' => $request->price]);

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Price updated successfully',
                'price' => $product->product_price
            ]);
        }

        return redirect()->back()->with('success', 'Price updated successfully');
    }
}
