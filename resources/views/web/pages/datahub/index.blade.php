@extends('web.layouts.app')

@section('title', __('PhyzioLine Data Hub'))

@push('meta')
    <meta name="description" content="{{ __('Explore global physical therapy data, statistics, and licensing requirements. Your central resource for PT insights.') }}">
    <meta name="keywords" content="Global PT Data, Statistics, Licensing, بنك المعلومات, احصائيات علاج طبيعي, Physical Therapy Landscape, تراخيص مزاولة المهنة">
@endpush

@section('content')
<div class="datahub-landing py-5" style="background-color: #f8f9fa; margin-top: 130px;">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center">
                <h1 class="display-4 font-weight-bold text-teal-700 mb-3" style="color: #0d9488;">{{ __('PhyzioLine Data Hub') }}</h1>
                <p class="lead text-muted">{{ __('Your central resource for global physical therapy insights and professional licensing guidance.') }}</p>
            </div>
        </div>

        <div class="row">
            <!-- Global Landscape Card -->
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 hover-lift">
                    <div class="card-body p-5 text-center">
                        <div class="icon-wrapper mb-4 text-teal-600" style="color: #0d9488; font-size: 3rem;">
                            <i class="las la-globe-americas"></i>
                        </div>
                        <h3 class="card-title font-weight-bold mb-3 text-dark" style="color: #333;">{{ __('Global Physical Therapy Landscape') }}</h3>
                        <p class="card-text text-muted mb-4">
                            {{ __('Explore interactive data on therapists, rehabilitation centers, and educational institutions across 100+ countries.') }}
                        </p>
                        <a href="{{ route('web.datahub.dashboard') }}" class="btn btn-lg btn-teal text-white px-5 rounded-pill" style="background-color: #0d9488; border-color: #0d9488;">
                            {{ __('Explore Dashboard') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Licensing Guide Card -->
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 hover-lift">
                    <div class="card-body p-5 text-center">
                        <div class="icon-wrapper mb-4 text-teal-600" style="color: #0d9488; font-size: 3rem;">
                            <i class="las la-graduation-cap"></i>
                        </div>
                        <h3 class="card-title font-weight-bold mb-3 text-dark" style="color: #333;">{{ __('Professional Equivalence & Licensing Guide') }}</h3>
                        <p class="card-text text-muted mb-4">
                            {{ __('Navigate the requirements for practicing physical therapy in renewed countries. Check equivalence and licensing steps.') }}
                        </p>
                        <a href="{{ route('web.datahub.licensing') }}" class="btn btn-lg btn-teal text-white px-5 rounded-pill" style="background-color: #0d9488; border-color: #0d9488;">
                            {{ __('Start Guide') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .btn-teal:hover {
        background-color: #0f766e !important;
        border-color: #0f766e !important;
    }


    body .bg-light.py-5 {
    margin-top: 150px !important;
    }
</style>
@endsection
