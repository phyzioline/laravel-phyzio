<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Mock notifications
        $notifications = collect([
            (object)['id' => 1, 'type' => 'appointment', 'title' => 'New Appointment Request', 'message' => 'John Doe requested an appointment for Dec 25th.', 'time' => '10 mins ago', 'unread' => true],
            (object)['id' => 2, 'type' => 'system', 'title' => 'System Update', 'message' => 'Platform maintenance scheduled for tonight.', 'time' => '1 hour ago', 'unread' => false],
        ]);

        return view('web.therapist.notifications.index', compact('notifications'));
    }
}
