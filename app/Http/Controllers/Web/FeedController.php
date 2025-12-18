<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FeedItem;
use App\Services\Feed\FeedTrackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    protected $trackingService;

    public function __construct(FeedTrackingService $service)
    {
        // $this->middleware('auth'); // Removed: Handled in routes
        $this->trackingService = $service;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Fetch Feed Items relevant to user
        $feedItems = FeedItem::forUser($user)->paginate(10);
        
        return view('web.feed.index', compact('feedItems'));
    }

    /**
     * AJAX endpoint to log interactions (clicks/views)
     */
    public function logInteraction(Request $request, $id)
    {
        $this->trackingService->logInteraction($id, $request->type, $request->meta ?? []);
        return response()->json(['status' => 'success']);
    }

    /**
     * AJAX endpoint to toggle like
     */
    public function toggleLike($id)
    {
        $liked = $this->trackingService->toggleLike($id);
        return response()->json(['liked' => $liked]);
    }

    /**
     * Generate Google Merchant Center Feed (XML)
     */
    public function google($lang = 'en')
    {
        $products = \App\Models\Product::with(['productImages', 'category'])
            // ->where('status', 'active') // Assuming all for now
            ->orderBy('id', 'desc')
            ->get();

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns:g="http://base.google.com/ns/1.0"/>');
        $channel = $xml->addChild('channel');
        
        $channel->addChild('title', 'Phyzioline Products (' . strtoupper($lang) . ')');
        $channel->addChild('link', url('/'));
        $channel->addChild('description', 'Medical and Therapy Products');

        foreach ($products as $product) {
            $item = $channel->addChild('item');
            
            // Basic fields
            $item->addChild('g:id', $product->id, 'http://base.google.com/ns/1.0');
            
            // Localized Title & Description
            $title = $lang == 'ar' ? ($product->product_name_ar ?? $product->product_name_en) : $product->product_name_en;
            $desc  = $lang == 'ar' ? ($product->product_desc_ar ?? $product->product_desc_en) : $product->product_desc_en;
            
            $item->addChild('g:title', htmlspecialchars($title ?? ''), 'http://base.google.com/ns/1.0');
            $item->addChild('g:description', htmlspecialchars(strip_tags($desc ?? '')), 'http://base.google.com/ns/1.0');
            
            // Link
            $item->addChild('g:link', route('product.show', $product->id), 'http://base.google.com/ns/1.0');
            
            // Image
            $imageUrl = $product->productImages->first() ? asset($product->productImages->first()->image) : asset('default/product.png');
            $item->addChild('g:image_link', $imageUrl, 'http://base.google.com/ns/1.0');
            
            // Price & Currency
            $item->addChild('g:price', $product->product_price . ' EGP', 'http://base.google.com/ns/1.0');
            
            // Availability
            $availability = $product->amount > 0 ? 'in stock' : 'out of stock';
            $item->addChild('g:availability', $availability, 'http://base.google.com/ns/1.0');
            
            // Required placeholders
            $item->addChild('g:condition', 'new', 'http://base.google.com/ns/1.0');
            $item->addChild('g:brand', $product->vendor->name ?? 'Phyzioline', 'http://base.google.com/ns/1.0'); 
        }

        return response($xml->asXML(), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
