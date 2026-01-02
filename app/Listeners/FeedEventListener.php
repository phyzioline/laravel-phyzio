<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Events\JobCreated;
use App\Services\Feed\FeedService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FeedEventListener implements ShouldQueue
{
    protected $feedService;

    /**
     * Create the event listener.
     */
    public function __construct(FeedService $feedService)
    {
        $this->feedService = $feedService;
    }

    /**
     * Handle ProductCreated events.
     */
    public function handleProductCreated(ProductCreated $event)
    {
        $this->feedService->createSystemPost($event->product, 'product');
    }

    /**
     * Handle JobCreated events.
     */
    public function handleJobCreated(JobCreated $event)
    {
        // Assuming Job model is passed
        $this->feedService->createSystemPost($event->job, 'job');
    }

    /**
     * Handle CourseCreated events.
     */
    public function handleCourseCreated(\App\Events\CourseCreated $event)
    {
        $this->feedService->createSystemPost($event->course, 'course');
    }

    /**
     * Handle TherapistVerified events.
     */
    public function handleTherapistVerified(\App\Events\TherapistVerified $event)
    {
        // Treat checking-in/verification as a "New Therapist" post
        // OR as "New Home Visit Availability" if they are a home visit therapist.
        // For simplicity: "New Therapist Joined"
        
        // We need to create a manual-style post but from system
        // OR enhance createSystemPost to handle 'user' type
        
       $this->feedService->createSystemPost($event->therapist, 'therapist');
    }

    /**
     * Handle the event.
     * Default handle method if needed, but we used targeted methods above.
     * We'll map them in EventServiceProvider.
     */
    public function handle($event)
    {
        //
    }
}
