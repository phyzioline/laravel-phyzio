@extends('web.help.layout')

@section('help_content')
<div class="row justify-content-center">
    <div class="col-12 mb-5 text-center">
        <h2 class="font-weight-bold" style="color: #36415a;">{{ __('Browse Categories') }}</h2>
        <p class="text-muted">{{ __('Select a topic to find answers') }}</p>
    </div>
</div>

<div class="row">
    @foreach ($categories as $key => $category)
    <div class="col-md-6 col-lg-4 mb-4">
        <a href="{{ route('help.category', $key) }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm border-0 hover-lift text-center p-4">
                <div class="card-body">
                    <div class="icon-wrapper mb-3">
                        <i class="{{ $category['icon'] }}" style="font-size: 48px; color: #02767F;"></i>
                    </div>
                    <h4 class="card-title font-weight-bold text-dark mb-2">{{ $category['title'] }}</h4>
                    <p class="card-text text-muted small">{{ $category['description'] }}</p>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<style>
    .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>
@endsection
