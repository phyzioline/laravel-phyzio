<?php

namespace App\Providers;

use App\Events\ProductCreated;
use App\Events\JobCreated;
use App\Listeners\FeedEventListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ProductCreated::class => [
            [FeedEventListener::class, 'handleProductCreated'],
        ],
        JobCreated::class => [
            [FeedEventListener::class, 'handleJobCreated'],
        ],
        \App\Events\CourseCreated::class => [
            [FeedEventListener::class, 'handleCourseCreated'],
        ],
        \App\Events\TherapistVerified::class => [
            [FeedEventListener::class, 'handleTherapistVerified'],
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
