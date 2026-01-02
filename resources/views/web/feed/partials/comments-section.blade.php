{{-- Comments Section Component --}}
<div class="comments-section mt-3 border-top pt-3">
    {{-- Comments Count --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-bold">{{ __('Comments') }} ({{ $feedItem->comments()->topLevel()->count() }})</h6>
        <button class="btn btn-sm btn-link text-muted" onclick="toggleCommentForm('{{ $feedItem->id }}')">
            <i class="bi bi-chat-dots"></i> {{ __('Add Comment') }}
        </button>
    </div>

    {{-- Comment Form --}}
    <div id="comment-form-{{ $feedItem->id }}" class="mb-3" style="display: none;">
        <form action="{{ route('feed.comments.store.' . app()->getLocale(), $feedItem->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="d-flex gap-2 align-items-start">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0" 
                     style="width: 32px; height: 32px; background: linear-gradient(135deg, #02767F, #04a5b8); font-size: 14px;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-grow-1">
                    <textarea name="comment_text" class="form-control form-control-sm border-0 bg-light" 
                              rows="2" placeholder="{{ __('Write a comment...') }}" maxlength="1000" required></textarea>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <input type="file" name="media" accept="image/*" class="form-control form-control-sm w-auto" style="max-width: 200px;">
                        <button type="submit" class="btn btn-sm" style="background: #02767F; color: white;">
                            {{ __('Post') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Comments List --}}
    <div class="comments-list">
        @forelse($feedItem->comments()->topLevel()->with('user', 'replies')->latest()->get() as $comment)
        <div class="comment mb-3 pb-3 border-bottom">
            <div class="d-flex gap-2">
                {{-- User Avatar --}}
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0" 
                     style="width: 32px; height: 32px; background: linear-gradient(135deg, #02767F, #04a5b8); font-size: 14px;">
                    {{ substr($comment->user->name, 0, 1) }}
                </div>

                <div class="flex-grow-1">
                    {{-- Comment Header --}}
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="fw-bold small">{{ $comment->user->name }}</span>
                            <span class="text-muted small ms-2">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        @if($comment->user_id == auth()->id())
                        <form action="{{ route('feed.comments.delete.' . app()->getLocale(), $comment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link btn-sm text-muted p-0" onclick="return confirm('{{ __('Delete this comment?') }}')">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                        @endif
                    </div>

                    {{-- Comment Text --}}
                    <p class="mb-2 small">{{ $comment->comment_text }}</p>

                    {{-- Comment Media --}}
                    @if($comment->media_url)
                    <img src="{{ asset('storage/' . $comment->media_url) }}" class="img-fluid rounded mb-2" style="max-height: 200px;">
                    @endif

                    {{-- Comment Actions --}}
                    <div class="d-flex gap-3 align-items-center">
                        <form action="{{ route('feed.comments.like.' . app()->getLocale(), $comment->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0" 
                                    style="color: {{ $comment->liked_by_user ? '#ef4444' : '#6b7280' }};">
                                <span style="font-size: 16px;">{{ $comment->liked_by_user ? '❤️' : '🤍' }}</span>
                                <small class="ms-1">{{ $comment->likes_count }}</small>
                            </button>
                        </form>
                        <button class="btn btn-link btn-sm text-muted text-decoration-none p-0" 
                                onclick="toggleReplyForm('{{ $comment->id }}')">
                            <small>{{ __('Reply') }}</small>
                        </button>
                    </div>

                    {{-- Reply Form --}}
                    <div id="reply-form-{{ $comment->id }}" class="mt-2" style="display: none;">
                        <form action="{{ route('feed.comments.store.' . app()->getLocale(), $feedItem->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <div class="d-flex gap-2">
                                <textarea name="comment_text" class="form-control form-control-sm border-0 bg-light" 
                                          rows="1" placeholder="{{ __('Write a reply...') }}" required></textarea>
                                <button type="submit" class="btn btn-sm btn-link" style="color: #02767F;">{{ __('Reply') }}</button>
                            </div>
                        </form>
                    </div>

                    {{-- Replies --}}
                    @if($comment->replies->count() > 0)
                    <div class="replies mt-3 ps-3 border-start" style="border-color: #e5e7eb !important;">
                        @foreach($comment->replies as $reply)
                        <div class="reply mb-2">
                            <div class="d-flex gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0" 
                                     style="width: 24px; height: 24px; background: linear-gradient(135deg, #02767F, #04a5b8); font-size: 11px;">
                                    {{ substr($reply->user->name, 0, 1) }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold small">{{ $reply->user->name }}</span>
                                        <span class="text-muted" style="font-size: 11px;">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mb-1 small">{{ $reply->comment_text }}</p>
                                    <form action="{{ route('feed.comments.like.' . app()->getLocale(), $reply->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0" 
                                                style="color: {{ $reply->liked_by_user ? '#ef4444' : '#6b7280' }}; font-size: 14px;">
                                            {{ $reply->liked_by_user ? '❤️' : '🤍' }}
                                            <small class="ms-1">{{ $reply->likes_count }}</small>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <p class="text-muted small text-center py-3">{{ __('No comments yet. Be the first to comment!') }}</p>
        @endforelse
    </div>
</div>

<script>
function toggleCommentForm(feedItemId) {
    const form = document.getElementById(`comment-form-${feedItemId}`);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function toggleReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
