<?php

namespace App\Services\Feed;

use App\Models\FeedMention;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MentionService
{
    /**
     * Parse text for @mentions and return array of mentioned usernames
     *
     * @param string $text
     * @return array
     */
    public function parseMentions($text)
    {
        // Match @username pattern (alphanumeric, underscore, dot)
        preg_match_all('/@(\w+(?:\.\w+)?)/', $text, $matches);
        
        return array_unique($matches[1] ?? []);
    }

    /**
     * Create mention records for mentioned users
     *
     * @param string $mentionableType
     * @param int $mentionableId
     * @param string $text
     * @return array Array of created mention IDs
     */
    public function createMentions($mentionableType, $mentionableId, $text)
    {
        $usernames = $this->parseMentions($text);
        
        if (empty($usernames)) {
            return [];
        }

        $mentionedUsers = User::whereIn('username', $usernames)->get();
        $mentionIds = [];
        $mentioner = Auth::user();

        foreach ($mentionedUsers as $user) {
            // Don't create mention if user mentions themselves
            if ($user->id === $mentioner->id) {
                continue;
            }

            $mention = FeedMention::create([
                'mentionable_type' => $mentionableType,
                'mentionable_id' => $mentionableId,
                'mentioned_user_id' => $user->id,
                'mentioner_user_id' => $mentioner->id
            ]);

            $mentionIds[] = $mention->id;

            // Create notification
            $this->notifyMention($user, $mentioner, $mentionableType, $mentionableId);
        }

        return $mentionIds;
    }

    /**
     * Create notification for mentioned user
     *
     * @param User $mentionedUser
     * @param User $mentioner
     * @param string $mentionableType
     * @param int $mentionableId
     */
    protected function notifyMention($mentionedUser, $mentioner, $mentionableType, $mentionableId)
    {
        $link = $this->getMentionLink($mentionableType, $mentionableId);

        Notification::create([
            'user_id' => $mentionedUser->id,
            'type' => 'mention',
            'data' => [
                'message' => "{$mentioner->name} mentioned you",
                'actor_name' => $mentioner->name,
                'actor_id' => $mentioner->id,
                'link' => $link,
                'type' => $this->getMentionTypeName($mentionableType)
            ]
        ]);
    }

    /**
     * Get link to the mention location
     *
     * @param string $mentionableType
     * @param int $mentionableId
     * @return string
     */
    protected function getMentionLink($mentionableType, $mentionableId)
    {
        if ($mentionableType === 'App\\Models\\FeedItem') {
            return route('feed.index.' . app()->getLocale()) . '#post-' . $mentionableId;
        }

        if ($mentionableType === 'App\\Models\\FeedComment') {
            $comment = \App\Models\FeedComment::find($mentionableId);
            if ($comment) {
                return route('feed.index.' . app()->getLocale()) . '#comment-' . $mentionableId;
            }
        }

        return route('feed.index.' . app()->getLocale());
    }

    /**
     * Get human-readable mention type name
     *
     * @param string $mentionableType
     * @return string
     */
    protected function getMentionTypeName($mentionableType)
    {
        return match($mentionableType) {
            'App\\Models\\FeedItem' => 'post',
            'App\\Models\\FeedComment' => 'comment',
            default => 'content'
        };
    }

    /**
     * Replace @mentions with clickable links
     *
     * @param string $text
     * @return string
     */
    public function linkifyMentions($text)
    {
        return preg_replace_callback('/@(\w+(?:\.\w+)?)/', function($matches) {
            $username = $matches[1];
            $user = User::where('username', $username)->first();
            
            if ($user) {
                $profileUrl = route('web.profile.index.' . app()->getLocale());
                return '<a href="' . $profileUrl . '" class="mention-link" style="color: #02767F; font-weight: 600; text-decoration: none;">@' . $username . '</a>';
            }
            
            return '@' . $username;
        }, $text);
    }

    /**
     * Search users for autocomplete
     *
     * @param string $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchUsers($query, $limit = 10)
    {
        return User::where('username', 'LIKE', $query . '%')
                   ->orWhere('name', 'LIKE', '%' . $query . '%')
                   ->limit($limit)
                   ->get(['id', 'name', 'username', 'type']);
    }
}
