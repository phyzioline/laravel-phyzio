<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($id);

        // Check if user already reviewed?
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        // Check if user has purchased this product (verified purchase)
        $hasPurchased = \App\Models\ItemsOrder::whereHas('order', function($q) {
                $q->where('user_id', Auth::id())
                  ->where('payment_status', 'paid');
            })
            ->where('product_id', $product->id)
            ->exists();
        
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
            'verified_purchase' => $hasPurchased,
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }
}
