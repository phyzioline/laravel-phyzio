@extends('web.layouts.dashboard_master')

@section('title', __('Help Center'))
@section('header_title', __('Help Center'))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="font-weight-bold">{{ __('Help Center') }}</h4>
            <form method="GET" action="{{ route('clinic.help-center.search') }}" class="d-flex">
                <input type="text" name="q" class="form-control mr-2" 
                       placeholder="{{ __('Search help articles...') }}" 
                       value="{{ request('q') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="las la-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Categories -->
<div class="row mb-4">
    @foreach($categories as $key => $category)
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; cursor: pointer;" 
             onclick="window.location.href='{{ route('clinic.help-center.index') }}#{{ $key }}'">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="{{ $category['icon'] }} fa-3x text-primary"></i>
                </div>
                <h5 class="font-weight-bold mb-2">{{ $category['title'] }}</h5>
                <p class="text-muted small mb-0">{{ $category['description'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Articles by Category -->
@foreach($categories as $key => $category)
    @php
        $categoryArticles = array_filter($articles, function($article) use ($key) {
            return $article['category'] === $key;
        });
    @endphp
    
    @if(count($categoryArticles) > 0)
    <div id="{{ $key }}" class="mb-5">
        <h5 class="font-weight-bold mb-3">
            <i class="{{ $category['icon'] }}"></i> {{ $category['title'] }}
        </h5>
        <div class="row">
            @foreach($categoryArticles as $article)
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <h6 class="font-weight-bold mb-2">
                            <a href="{{ route('clinic.help-center.show', $article['slug']) }}" class="text-dark">
                                {{ $article['title'] }}
                            </a>
                        </h6>
                        <p class="text-muted small mb-0">{{ Str::limit($article['content'], 100) }}</p>
                        <a href="{{ route('clinic.help-center.show', $article['slug']) }}" class="btn btn-sm btn-link p-0 mt-2">
                            {{ __('Read more') }} <i class="las la-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
@endforeach
@endsection

