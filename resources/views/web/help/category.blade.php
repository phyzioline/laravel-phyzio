@extends('web.help.layout')

@php
    $breadcrumbs = [
        ['title' => $category['title'], 'url' => route('help.' . app()->getLocale() . '.category', $category['slug'])]
    ];
@endphp

@section('help_content')
<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="font-weight-bold mb-3">{{ __('Categories') }}</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('help.' . app()->getLocale() . '.index') }}" class="text-muted"><i class="las la-angle-left mr-2"></i> {{ __('Back to Home') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-wrapper mr-3">
                <i class="{{ $category['icon'] }}" style="font-size: 40px; color: #02767F;"></i>
            </div>
            <h2 class="font-weight-bold m-0" style="color: #36415a;">{{ $category['title'] }}</h2>
        </div>
        <p class="lead text-muted mb-5">{{ $category['description'] }}</p>

        <div class="list-group shadow-sm">
            @foreach ($category['articles'] as $key => $article)
                <a href="{{ route('help.' . app()->getLocale() . '.article', ['category' => $category['slug'], 'article' => $key]) }}" class="list-group-item list-group-item-action p-4 border-left-0 border-right-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-1">{{ $article['title'] }}</h5>
                        <p class="mb-0 text-muted small">{{ __('Click to read more') }}</p>
                    </div>
                    <i class="las la-angle-right text-muted"></i>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
