@extends('web.layouts.dashboard_master')

@section('title', $article['title'])
@section('header_title', $article['title'])

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="mb-4">
                    <a href="{{ route('clinic.help-center.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="las la-arrow-left"></i> {{ __('Back to Help Center') }}
                    </a>
                </div>
                
                <h2 class="font-weight-bold mb-3">{{ $article['title'] }}</h2>
                
                @if($article['video_url'] ?? null)
                <div class="mb-4">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="{{ $article['video_url'] }}" allowfullscreen></iframe>
                    </div>
                </div>
                @endif
                
                <div class="article-content">
                    {!! nl2br(e($article['content'])) !!}
                </div>
            </div>
        </div>
        
        @if(count($relatedArticles) > 0)
        <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="font-weight-bold mb-0">{{ __('Related Articles') }}</h5>
            </div>
            <div class="card-body px-4">
                <ul class="list-unstyled">
                    @foreach($relatedArticles as $related)
                    <li class="mb-2">
                        <a href="{{ route('clinic.help-center.show', $related['slug']) }}" class="text-primary">
                            <i class="las la-file-alt"></i> {{ $related['title'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="font-weight-bold mb-0">{{ __('Need More Help?') }}</h6>
            </div>
            <div class="card-body px-4">
                <p class="text-muted small">{{ __('Can\'t find what you\'re looking for?') }}</p>
                <a href="mailto:support@phyzioline.com" class="btn btn-primary btn-block">
                    <i class="las la-envelope"></i> {{ __('Contact Support') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

