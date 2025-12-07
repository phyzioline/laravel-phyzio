<?php

namespace App\Http\Controllers\Web;

use App\Models\CompareItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompareController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('view_login')->with('message', [
                'type' => 'error',
                'text' => __('Please login to compare products')
            ]);
        }

        $compareItems = CompareItem::where('user_id', Auth::id())
            ->with('product.productImages', 'product.category', 'product.sub_category')
            ->get();

        return view('web.pages.compare', compact('compareItems'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('Please login to compare products')
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $existing = CompareItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success' => true,
                'message' => __('Product removed from compare'),
                'action' => 'removed'
            ]);
        }

        // Limit to 4 products for comparison
        $count = CompareItem::where('user_id', Auth::id())->count();
        if ($count >= 4) {
            return response()->json([
                'success' => false,
                'message' => __('You can compare maximum 4 products')
            ], 400);
        }

        CompareItem::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Product added to compare'),
            'action' => 'added',
            'count' => $count + 1
        ]);
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('view_login');
        }

        CompareItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        return redirect()->route('compare.index')->with('message', [
            'type' => 'success',
            'text' => __('Product removed from compare')
        ]);
    }
}

