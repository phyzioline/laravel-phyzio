@extends('web.help.layout')

@php
    $breadcrumbs = [
        ['title' => $category['title'], 'url' => route('help.category', $category['slug'])],
        ['title' => $article['title'], 'url' => '#']
    ];
@endphp

@section('help_content')
<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm border-0 sticky-top" style="top: 100px; z-index: 1;">
            <div class="card-body">
                <h5 class="font-weight-bold mb-3">{{ __('In this section') }}</h5>
                <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                    @foreach ($category['articles'] as $key => $item)
                        <a class="nav-link {{ $key === request()->article ? 'active' : '' }} mb-1" 
                           href="{{ route('help.article', ['category' => $category['slug'], 'article' => $key]) }}"
                           style="{{ $key === request()->article ? 'background-color: #02767F;' : 'color: #36415a;' }}">
                            {{ $item['title'] }}
                        </a>
                    @endforeach
                </div>
                
                <hr class="my-4">
                
                <h6 class="font-weight-bold mb-2">{{ __('Other Categories') }}</h6>
                <ul class="list-unstyled small">
                    @foreach($categories as $catKey => $cat)
                        @if($catKey !== $category['slug'])
                        <li class="mb-2">
                            <a href="{{ route('help.category', $catKey) }}" class="text-muted text-decoration-none">
                                <i class="{{ $cat['icon'] }} mr-1"></i> {{ $cat['title'] }}
                            </a>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="bg-white p-5 shadow-sm rounded">
            <h1 class="font-weight-bold mb-4" style="color: #36415a;">{{ $article['title'] }}</h1>
            
            <div class="article-content text-muted" style="line-height: 1.8; font-size: 1.05rem;">
                {!! $article['content'] !!}
            </div>

            <div class="mt-5 pt-4 border-top">
                <p class="font-weight-bold small text-uppercase text-muted mb-3">{{ __('Was this article helpful?') }}</p>
                <button class="btn btn-outline-success btn-sm mr-2 px-3 rounded-pill"><i class="las la-thumbs-up mr-1"></i> {{ __('Yes') }}</button>
                <button class="btn btn-outline-danger btn-sm px-3 rounded-pill"><i class="las la-thumbs-down mr-1"></i> {{ __('No') }}</button>
            </div>

            <div class="mt-5 p-4 rounded bg-light border border-light">
                <div class="d-flex align-items-start">
                    <div class="mr-3 text-primary">
                        <i class="las la-headset la-3x" style="color: #02767F;"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mb-1" style="color: #36415a;">{{ __('Still need help?') }}</h5>
                        <p class="text-muted small mb-2">{{ __('Our support team is available to assist you.') }}</p>
                        
                        <div class="row small text-muted">
                            <div class="col-md-6 mb-2">
                                <i class="las la-envelope mr-1"></i> {{ __('Email') }}: <strong class="text-dark">support@phyzioline.com</strong>
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="las la-clock mr-1"></i> {{ __('Hours') }}: <strong class="text-dark">{{ __('Sun - Thu, 09:00 AM - 05:00 PM') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
