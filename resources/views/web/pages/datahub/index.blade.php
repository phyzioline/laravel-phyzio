@extends('web.layouts.app')

@section('title', 'PhyzioLine Data Hub')

@section('content')
<div class="data-hub-landing" style="background: linear-gradient(135deg, #004d40 0%, #00695c 100%); color: white; min-height: 80vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: 50px 0;">
    <div class="container">
        <h1 class="display-3 font-weight-bold mb-4" style="text-shadow: 2px 2px 10px rgba(0,0,0,0.5);">PhyzioLine Data Hub</h1>
        <p class="lead mb-5" style="font-size: 1.5rem; opacity: 0.9;">The World's Most Comprehensive Physical Therapy Intelligence Platform.</p>
        
        <div class="row justify-content-center">
            <div class="col-md-5 mb-4">
                <a href="{{ route('web.datahub.dashboard') }}" class="card-link text-decoration-none">
                    <div class="card bg-white text-dark shadow-lg border-0 h-100 transform-hover" style="transition: transform 0.3s;">
                        <div class="card-body p-5">
                            <i class="las la-globe-americas display-2 text-primary mb-3"></i>
                            <h3 class="card-title font-weight-bold">Global Dashboard</h3>
                            <p class="card-text text-muted">Explore the global landscape of Physical Therapy. Population, therapists, schools, and metrics for 100+ countries.</p>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-5 mb-4">
                <a href="{{ route('web.datahub.licensing') }}" class="card-link text-decoration-none">
                    <div class="card bg-white text-dark shadow-lg border-0 h-100 transform-hover" style="transition: transform 0.3s;">
                        <div class="card-body p-5">
                            <i class="las la-certificate display-2 text-success mb-3"></i>
                            <h3 class="card-title font-weight-bold">Licensing Guide</h3>
                            <p class="card-text text-muted">Navigate professional equivalence, licensing exams, and requirements for working abroad.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .transform-hover:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2) !important;
    }
</style>
@endsection
