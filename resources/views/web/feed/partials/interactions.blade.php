<div class="d-flex justify-content-between align-items-center border-top pt-2">
     <div>
         <button class="btn btn-light btn-sm rounded-pill px-3 mr-2 like-btn {{ $item->liked_by_user ? 'text-primary' : '' }}" onclick="toggleLike({{ $item->id }})">
             <i class="las la-thumbs-up"></i> <span class="like-count">{{ $item->likes_count }}</span>
         </button>
         <button class="btn btn-light btn-sm rounded-pill px-3">
             <i class="las la-share"></i> {{ __('Share') }}
         </button>
     </div>
     <small class="text-muted">{{ $item->views_count }} {{ __('Views') }}</small>
 </div>
