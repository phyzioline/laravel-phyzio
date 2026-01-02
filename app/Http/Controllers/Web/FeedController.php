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

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Start with base query scoped for user
        $query = FeedItem::forUser($user);

        // Apply filters
        if ($request->has('type')) {
            $type = $request->input('type');

            if ($type === 'my_posts') {
                // Determine if we should look for 'post' type authored by user, 
                // or ANY content sourceable by user. 
                // For now, let's assume 'post' type created by user.
                // Or robustly: sourceable_id = user->id AND sourceable_type = User class
                $query->where('sourceable_id', $user->id)
                      ->where('sourceable_type', get_class($user));
            } elseif (in_array($type, ['course', 'product', 'job', 'therapist'])) {
                $query->where('type', $type);
            }
        }
        
        $feedItems = $query->paginate(10);
        
        return view('web.feed.index', compact('feedItems'));
    }

    /**
     * Store a new user post in the feed
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|max:2048' // 2MB Max
        ]);

        $user = Auth::user();
        
        $post = new FeedItem();
        $post->type = 'post';
        $post->title = $user->name; // User name as title for posts
        $post->description = $request->content;
        $post->status = 'active';
        $post->target_audience = ['all']; // Visible to everyone
        $post->ai_relevance_base_score = 1.0;
        $post->scheduled_at = now();

        // Polymorphic link to user
        $post->sourceable_id = $user->id;
        $post->sourceable_type = get_class($user);

        if ($request->hasFile('image')) {
             $path = $request->file('image')->store('feed_uploads', 'public');
             $post->media_url = asset('storage/' . $path);
        }

        $post->save();

        return redirect()->back()->with('success', 'Post published successfully!');
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
