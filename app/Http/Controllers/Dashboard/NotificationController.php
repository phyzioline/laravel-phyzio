<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        
        // Mark unread as read when viewing the list? 
        // Or let user do it explicitly? Usually valid to mark database 'read_at' 
        // but for now we just list them.
        
        return view('dashboard.pages.notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read and redirect to its action.
     */
    public function read($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Check if there is a URL to redirect to
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }
        
        // Or if it's an order notification
        if (isset($notification->data['order_id'])) {
             // Determine route based on user type
             if(Auth::user()->type === 'vendor') {
                 return redirect()->route('dashboard.orders.show', $notification->data['order_id']);
             }
             return redirect()->route('dashboard.orders.show', $notification->data['order_id']);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function readAll()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
}
