<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Get real notifications from database
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function($notification) {
                $data = $notification->data;
                $type = $data['type'] ?? 'system';
                
                // Map notification types
                $typeMap = [
                    'home_visit' => 'home_visit',
                    'appointment' => 'appointment',
                    'system' => 'system'
                ];
                
                return (object)[
                    'id' => $notification->id,
                    'type' => $typeMap[$type] ?? 'system',
                    'title' => $data['title'] ?? $data['message'] ?? 'Notification',
                    'message' => $data['message'] ?? $data['title'] ?? 'You have a new notification',
                    'time' => $notification->created_at->diffForHumans(),
                    'unread' => $notification->read_at === null
                ];
            });

        return view('web.therapist.notifications.index', compact('notifications'));
    }
}
