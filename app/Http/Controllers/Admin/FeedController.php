<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedItem;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = FeedItem::latest()->paginate(20);
        return view('admin.feed.index', compact('items'));
    }

    public function create()
    {
        return view('admin.feed.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'type' => 'required',
            'target_audience' => 'required|array',
            'action_link' => 'required|url',
        ]);
        
        // Manual feed item creation (e.g. for News/Tips)
        FeedItem::create(array_merge($data, [
            'status' => 'active', 
            'scheduled_at' => now()
        ]));

        return redirect()->route('admin.feed.index')->with('success', 'Feed Item Published');
    }
    
    public function destroy(FeedItem $item)
    {
        $item->delete();
        return back()->with('success', 'Deleted');
    }
}
