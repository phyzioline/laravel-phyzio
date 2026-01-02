@extends('web.layouts.dashboard_master')

@section('title', __('Select Assessment Template'))
@section('header_title', __('Select Assessment Template'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title font-weight-bold mb-0">{{ __('Select Assessment Template') }}</h5>
                        <p class="text-muted mb-0 small">{{ __('Choose a template to start your assessment') }}</p>
                    </div>
                    <a href="{{ route('clinic.episodes.show', $episode) }}" class="btn btn-secondary">
                        <i class="las la-arrow-left"></i> {{ __('Back') }}
                    </a>
                </div>
            </div>
            <div class="card-body px-4">
                <div class="row">
                    @forelse($templates as $template)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border template-card" style="cursor: pointer; transition: all 0.3s;" 
                             onclick="window.location.href='{{ route('clinic.assessments.create', ['episode' => $episode->id, 'template_id' => $template->id]) }}'">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px; background-color: #00897b;">
                                        <i class="las la-clipboard-list fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="font-weight-bold mb-1">{{ $template->name }}</h6>
                                        @if($template->condition_code)
                                        <small class="text-muted">ICD: {{ $template->condition_code }}</small>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($template->description)
                                <p class="text-muted small mb-3">{{ Str::limit($template->description, 100) }}</p>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($template->specialty)
                                        <span class="badge badge-info">{{ ucfirst($template->specialty) }}</span>
                                        @endif
                                        @if($template->is_system_template)
                                        <span class="badge badge-success">{{ __('System Template') }}</span>
                                        @endif
                                    </div>
                                    <i class="las la-arrow-right text-primary"></i>
                                </div>
                                
                                @if($template->usage_count > 0)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="las la-chart-line"></i> {{ __('Used') }} {{ $template->usage_count }} {{ __('times') }}
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="las la-info-circle fa-2x mb-3"></i>
                            <p>{{ __('No assessment templates available.') }}</p>
                            <a href="{{ route('clinic.assessments.create', $episode) }}" class="btn btn-primary">
                                {{ __('Create Assessment Without Template') }}
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
                
                <!-- Option to create without template -->
                <div class="mt-4 text-center">
                    <a href="{{ route('clinic.assessments.create', $episode) }}" class="btn btn-outline-secondary">
                        <i class="las la-file-alt"></i> {{ __('Create Assessment Without Template') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .template-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
        border-color: #00897b !important;
    }
</style>
@endpush
@endsection

