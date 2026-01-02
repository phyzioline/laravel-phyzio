<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FeedItem;
use App\Models\FeedComment;
use App\Models\CommentLike;
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
                $query->where('sourceable_type', 'App\Models\User')
                      ->where('sourceable_id', $user->id);
            } else {
                $query->where('type', $type);
            }
        }

        $feedItems = $query->with('sourceable')->paginate(15);
        
        // Prepare clean JSON data for JavaScript
        $feedData = $feedItems->map(function($item) {
            $author = $item->sourceable;
            return [
                'id' => $item->id,
                'type' => $item->type,
                'author' => [
                    'name' => $author ? $author->name : 'Phyzioline System',
                    'role' => $item->sourceable_type == 'App\Models\User' ? ($author->type ?? 'user') : 'admin',
                    'avatar' => $author && isset($author->profile_photo) ? $author->profile_photo : 'https://placehold.co/100x100/02767F/white?text=P',
                    'verified' => true
                ],
                'timestamp' => $item->created_at->diffForHumans(),
                'content' => [
                    'text' => $item->description ?? '',
                    'title' => $item->title ?? '',
                ],
                'media' => $item->media_url ? ['type' => 'image', 'url' => $item->media_url] : null,
                'metrics' => [
                    'likes' => $item->likes_count ?? 0,
                    'comments' => $item->comments_count ?? 0
                ],
                'action' => [
                    'label' => $item->action_text ?? __('View Details'),
                    'link' => $item->action_link ?? '#'
                ]
            ];
        });

        return view('web.feed.index', [
            'feedItems' => $feedItems,
            'feedData' => $feedData
        ]);
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
     * Like a feed item.
     */
    public function like($id)
    {
        $item = FeedItem::findOrFail($id);
        // Implement like logic here
        return redirect()->back()->with('message', ['type' => 'success', 'text' => 'Liked!']);
    }

    /**
     * Store a new comment on a feed item.
     */
    public function storeComment(Request $request, $feedItemId)
    {
        $validated = $request->validate([
            'comment_text' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:feed_comments,id',
            'media' => 'nullable|image|max:5120' // 5MB max
        ]);

        $mediaUrl = null;
        if ($request->hasFile('media')) {
            $mediaUrl = $request->file('media')->store('comments', 'public');
        }

        $comment = FeedComment::create([
            'feed_item_id' => $feedItemId,
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'comment_text' => $validated['comment_text'],
            'media_url' => $mediaUrl
        ]);

        // Increment comment count on feed item
        $feedItem = FeedItem::find($feedItemId);
        if ($feedItem) {
            $feedItem->increment('comments_count');
        }

        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => __('Comment posted successfully!')
        ]);
    }

    /**
     * Delete a comment.
     */
    public function deleteComment($commentId)
    {
        $comment = FeedComment::findOrFail($commentId);
        
        // Check authorization
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Decrement feed item comment count
        $feedItem = $comment->feedItem;
        if ($feedItem) {
            $feedItem->decrement('comments_count');
        }

        $comment->delete();

        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => __('Comment deleted successfully!')
        ]);
    }

    /**
     * Like/unlike a comment.
     */
    public function likeComment($commentId)
    {
        $comment = FeedComment::findOrFail($commentId);
        $userId = Auth::id();

        $existingLike = CommentLike::where('comment_id', $commentId)
                                                  ->where('user_id', $userId)
                                                  ->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $comment->decrement('likes_count');
            $message = __('Comment unliked');
        } else {
            // Like
            CommentLike::create([
                'comment_id' => $commentId,
                'user_id' => $userId
            ]);
            $comment->increment('likes_count');
            $message = __('Comment liked!');
        }

        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => $message
        ]);
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
