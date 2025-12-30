@extends('web.layouts.app')

@section('content')
<div class="help-center-wrapper" style="background-color: #f8f9fa; min-height: 80vh;">
    <!-- specific help center header if needed, otherwise uses main header -->
    <div class="help-search-area py-5" style="background: linear-gradient(135deg, #02767F 0%, #004d57 100%);">
        <div class="container text-center text-white">
            <h1 class="font-weight-bold mb-3">{{ __('How can we help you?') }}</h1>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form action="{{ route('help.' . app()->getLocale() . '.search') }}" method="GET">
                        <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                            <input type="text" name="q" class="form-control border-0 px-4" placeholder="{{ __('Search for answers...') }}" value="{{ request('q') }}">
                            <div class="input-group-append">
                                <button class="btn btn-light px-4 border-0" type="submit" style="color: #02767F;">
                                    <i class="las la-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        @if(isset($breadcrumbs))
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('help.' . app()->getLocale() . '.index') }}">{{ __('Help Center') }}</a></li>
                @foreach($breadcrumbs as $crumb)
                    @if($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ $crumb['title'] }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ $crumb['url'] }}">{{ $crumb['title'] }}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>
        @endif

        @yield('help_content')
    </div>
</div>
@endsection
