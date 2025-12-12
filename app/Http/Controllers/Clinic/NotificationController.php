<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = collect([
            (object)['title' => 'New Doctor Registered', 'message' => 'Dr. Smith has joined the Orthopedics department.', 'time' => '2 hours ago', 'type' => 'info'],
            (object)['title' => 'System Maintenance', 'message' => 'Scheduled downtime at midnight.', 'time' => '1 day ago', 'type' => 'warning'],
        ]);
        return view('web.clinic.notifications.index', compact('notifications'));
    }
}
