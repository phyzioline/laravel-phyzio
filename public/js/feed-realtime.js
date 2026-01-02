// Real-Time Feed Updates with Pusher/Laravel Echo
// This file provides real-time functionality for the feed

// NOTE: To use this feature, you need to:
// 1. Install Laravel Echo and Pusher JS:
//    npm install --save laravel-echo pusher-js
// 2. Configure broadcasting in .env:
//    BROADCAST_DRIVER=pusher
//    PUSHER_APP_ID=your-app-id
//    PUSHER_APP_KEY=your-app-key
//    PUSHER_APP_SECRET=your-app-secret
//    PUSHER_APP_CLUSTER=your-cluster
// 3. Run: php artisan config:cache

// Example integration (uncomment and configure when ready):
/*
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Listen for new posts
window.Echo.channel('feed')
    .listen('.post.created', (e) => {
        console.log('New post created:', e);
        
        // Show notification
        showNotification('New post from ' + e.author.name);
        
        // Optionally reload feed or prepend new post
        // prependNewPost(e);
    })
    .listen('.comment.added', (e) => {
        console.log('New comment added:', e);
        
        // Increment comment count for the feed item
        updateCommentCount(e.feed_item_id);
    })
    .listen('.post.liked', (e) => {
        console.log('Post liked:', e);
        
        // Update like count in UI
        updateLikeCount(e.feed_item_id, e.likes_count);
    });

function showNotification(message) {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.className = 'alert alert-info position-fixed top-0 end-0 m-3';
    toast.style.zIndex = '9999';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 3000);
}

function updateCommentCount(feedItemId) {
    const element = document.querySelector(`[data-feed-id="${feedItemId}"] .comment-count`);
    if (element) {
        const current = parseInt(element.textContent) || 0;
        element.textContent = current + 1;
    }
}

function updateLikeCount(feedItemId, newCount) {
    const element = document.querySelector(`[data-feed-id="${feedItemId}"] .like-count`);
    if (element) {
        element.textContent = newCount;
    }
}
*/

// Fallback: Simple polling implementation (works without WebSockets)
// This checks for new content every 30 seconds
let lastFeedCheck = new Date().getTime();

function checkForNewPosts() {
    fetch(window.location.href + '?since=' + lastFeedCheck)
        .then(response => response.json())
        .then(data => {
            if (data.new_posts && data.new_posts.length > 0) {
                const badge = document.querySelector('.new-posts-badge');
                if (badge) {
                    badge.textContent = data.new_posts.length;
                    badge.style.display = 'inline-block';
                }
            }
            lastFeedCheck = new Date().getTime();
        })
        .catch(error => console.log('Feed check failed:', error));
}

// Check every 30 seconds (optional - disable if using WebSockets)
// setInterval(checkForNewPosts, 30000);
