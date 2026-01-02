<?php

namespace App\Services\Feed;

use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class FeedService
{
    /**
     * Create a system-generated post (Product, Job, Course, etc.)
     *
     * @param Model $source The source model (Product, Job, Course)
     * @param string $type The type of post ('product', 'job', 'course')
     * @return FeedItem|null
     */
    public function createSystemPost(Model $source, string $type)
    {
        try {
            $post = new FeedItem();
            $post->type = $type;
            $post->sourceable_id = $source->id;
            $post->sourceable_type = get_class($source);
            $post->status = 'active';
            $post->scheduled_at = now();
            $post->ai_relevance_base_score = 0.8; // Default for system posts
            $post->target_audience = ['all']; // Default, can refine based on type

            // Set content based on type (Bilingual support handled in view, but we store main title/desc)
            // We'll store basic info, view will pull dynamic localized data from source relation if needed
            // But for performance, we might cache some here. 
            // For now, let's store English as fallback/primary title, view handles locale.
            
            switch ($type) {
                case 'product':
                    $post->title = $source->product_name_en; // Fallback title
                    $post->description = $source->short_description_en ?? 'New Product Available';
                    // Image logic: get fitst image
                    $image = $source->productImages->first();
                    $post->media_url = $image ? asset($image->image) : null;
                    $post->action_text = 'Buy Now';
                    $post->action_link = route('product.show.en', $source->id); // Link depends on locale at runtime, store generic or handle in view
                    // Storing localized route might be tricky. Better to store ID and generate route in View.
                    // But FeedItem has 'action_link'. Let's store a neutral path or handle in view.
                    // Actually, better to leave action_link empty if it's dynamic, OR store a relative path using 'en' as base.
                    $post->action_link = route('product.show.en', $source->id); 
                    break;

                case 'job':
                    $post->title = $source->title;
                    $post->description = $source->company_name . ' is hiring for ' . $source->location;
                    $post->media_url = $source->company_logo ?? asset('assets/images/defaults/job.png');
                    $post->action_text = 'Apply Now';
                    $post->action_link = route('web.jobs.show.en', $source->id);
                    break;

                case 'course':
                    $post->title = $source->title; // Course uses 'title'
                    $post->description = $source->description; // Course uses 'description' (short_description might not exist)
                    $post->media_url = $source->thumbnail ? asset('storage/'.$source->thumbnail) : null;
                    $post->action_text = 'Enroll Now';
                    $post->action_link = route('web.courses.show.' . app()->getLocale(), $source->id); 
                    // Note: Link stored here will be fixed to the locale when created. 
                    // To handle dynamic locale, partials should reconstruct the link.
                    break;

                case 'therapist':
                    $post->title = $source->name;
                    $post->description = $source->therapistProfile->bio ?? 'Verified Professional Therapist available for Home Visits.';
                    $post->media_url = $source->profile_photo_path ? asset('storage/' . $source->profile_photo_path) : null;
                    $post->action_text = 'Book Now';
                    $post->action_link = route('web.home_visits.book.' . app()->getLocale(), $source->id);
                    break;

                default:
                    return null;
            }

            $post->save();
            return $post;

        } catch (\Exception $e) {
            Log::error("Failed to create system feed post for {$type} ID {$source->id}: " . $e->getMessage());
            return null; // Fail silently to not block the main action
        }
    }

    /**
     * Create a manual user post
     */
    public function createManualPost(User $user, array $data)
    {
        $post = new FeedItem();
        $post->type = 'post';
        $post->sourceable_id = $user->id;
        $post->sourceable_type = get_class($user);
        $post->title = $user->name;
        $post->description = $data['content'];
        $post->status = 'active';
        $post->scheduled_at = now();
        $post->target_audience = ['all'];
        $post->ai_relevance_base_score = 1.0;

        if (isset($data['media_url'])) {
            $post->media_url = $data['media_url'];
        }

        $post->save();
        return $post;
    }
}
