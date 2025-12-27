<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackMail;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('web.pages.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Save to Database
        try {
            \App\Models\Feedback::create($validated);
        } catch (\Exception $e) {
            // Log error but continue to send email if possible, or handle gracefully
            \Log::error('Failed to save feedback: ' . $e->getMessage());
        }

        // Send email logic
        try {
            // Check if Mail implementation is ready, otherwise just log or "fake" it for now if no mail class exists
            // For now, I will use a raw mail or assume FeedbackMail exists. 
            // Better: use direct Mail::send to avoid creating another file if possible, or create the Mailable.
            
            Mail::send('web.mail.feedback', ['data' => $validated], function ($message) use ($validated) {
                $message->to('phyzioline@gmail.com')
                        ->subject('New Feedback: ' . $validated['subject']);
                $message->from($validated['email'], $validated['first_name'] . ' ' . $validated['last_name']);
            });

            return response()->json(['success' => true, 'message' => 'Your message has been sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send message. Please try again later.'], 500);
        }
    }
}
