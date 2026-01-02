<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TherapistVerified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $therapist;

    /**
     * Create a new event instance.
     */
    public function __construct(User $therapist)
    {
        $this->therapist = $therapist;
    }
}
