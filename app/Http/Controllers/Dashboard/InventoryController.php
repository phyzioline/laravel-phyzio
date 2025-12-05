<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    /**
     * Display inventory management page
     */
    public function manage(Request $request)
    {
        $query = Product::with(['category', 'sub_category', 'productImages']);

        // Apply filters
        if ($request->has('listing_status') && $request->listing_status != '') {
            if ($request->listing_status == 'active') {
                $query->where('status', 'active');
            } else {
                $query->where('status', 'inactive');
            }
        }

        // Stock level filters
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'low_stock':
                    $query->where('amount', '>', 0)->where('amount', '<=', 10);
                    break;
                case 'out_of_stock':
                    $query->where('amount', 0);
                    break;
                case 'in_stock':
                    $query->where('amount', '>', 10);
                    break;
            }
        }

        // Search by SKU, Name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('product_name_en', 'like', "%{$search}%")
                  ->orWhere('product_name_ar', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(50);
        $categories = Category::where('status', 'active')->get();

        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'low_stock' => Product::where('amount', '>', 0)->where('amount', '<=', 10)->count(),
            'out_of_stock' => Product::where('amount', 0)->count(),
        ];

        return view('dashboard.pages.inventory.manage', compact('products', 'categories', 'stats'));
    }

    /**
     * Display stock levels page
     */
    public function stockLevels()
    {
        $products = Product::with(['category', 'productImages'])
            ->orderBy('amount', 'asc')
            ->paginate(25);

        $lowStockProducts = Product::where('amount', '>', 0)
            ->where('amount', '<=', 10)
            ->count();

        $outOfStockProducts = Product::where('amount', 0)->count();

        return view('dashboard.pages.inventory.stock-levels', compact('products', 'lowStockProducts', 'outOfStockProducts'));
    }

    /**
     * Display inventory reports
     */
    public function reports()
    {
        $totalValue = Product::selectRaw('SUM(product_price * amount) as total_value')->first()->total_value ?? 0;
        $totalProducts = Product::count();
        $totalStock = Product::sum('amount');

        $categoryBreakdown = Product::selectRaw('category_id, COUNT(*) as product_count, SUM(amount) as total_stock')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return view('dashboard.pages.inventory.reports', compact('totalValue', 'totalProducts', 'totalStock', 'categoryBreakdown'));
    }

    /**
     * Update stock for a product
     */
    public function updateStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->update(['amount' => $request->amount]);

        return redirect()->back()->with('success', 'Stock updated successfully');
    }
}
