@extends('web.layouts.dashboard_master')

@section('title', 'Intake Form')
@section('header_title', 'Intake Form Details')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<div class="row">
    <!-- Form Details -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="font-weight-bold mb-0">{{ $form->name }}</h5>
                    @if($form->description)
                        <small class="text-muted">{{ $form->description }}</small>
                    @endif
                </div>
                <div>
                    <a href="{{ route('clinic.intake-forms.edit', $form->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="las la-edit"></i> {{ __('Edit') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>{{ __('Status') }}:</strong>
                    @if($form->is_active)
                        <span class="badge badge-success">{{ __('Active') }}</span>
                    @else
                        <span class="badge badge-secondary">{{ __('Inactive') }}</span>
                    @endif
                    
                    <strong class="ml-3">{{ __('Required') }}:</strong>
                    @if($form->is_required)
                        <span class="badge badge-warning">{{ __('Yes') }}</span>
                    @else
                        <span class="badge badge-secondary">{{ __('No') }}</span>
                    @endif
                </div>

                <h6 class="font-weight-bold mb-3">{{ __('Form Fields') }}</h6>
                <div class="form-preview">
                    @foreach($form->form_fields as $key => $field)
                    <div class="form-group mb-3 p-3 border rounded">
                        <label class="font-weight-bold">
                            {{ $field['label'] ?? $key }}
                            @if(isset($field['required']) && $field['required'])
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        <div class="mt-2">
                            @if($field['type'] === 'text' || $field['type'] === 'email' || $field['type'] === 'tel')
                                <input type="{{ $field['type'] }}" class="form-control" disabled placeholder="{{ __('Sample input') }}">
                            @elseif($field['type'] === 'textarea')
                                <textarea class="form-control" rows="3" disabled placeholder="{{ __('Sample textarea') }}"></textarea>
                            @elseif($field['type'] === 'select')
                                <select class="form-control" disabled>
                                    <option>{{ __('Select') }}</option>
                                    @foreach($field['options'] ?? [] as $option)
                                        <option>{{ $option }}</option>
                                    @endforeach
                                </select>
                            @elseif($field['type'] === 'radio')
                                @foreach($field['options'] ?? [] as $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" disabled>
                                    <label class="form-check-label">{{ $option }}</label>
                                </div>
                                @endforeach
                            @elseif($field['type'] === 'checkbox')
                                @foreach($field['options'] ?? [] as $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">{{ $option }}</label>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <small class="text-muted">{{ __('Type') }}: {{ $field['type'] }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Responses Sidebar -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="font-weight-bold mb-0">{{ __('Form Responses') }}</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h3 class="mb-0">{{ $responses->total() ?? 0 }}</h3>
                    <small class="text-muted">{{ __('Total Responses') }}</small>
                </div>
                
                @if(isset($responses) && $responses->count() > 0)
                <div class="list-group">
                    @foreach($responses as $response)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $response->patient->first_name ?? '' }} {{ $response->patient->last_name ?? '' }}</strong>
                                <br>
                                <small class="text-muted">{{ $response->submitted_at ? $response->submitted_at->format('M d, Y') : $response->created_at->format('M d, Y') }}</small>
                            </div>
                            <span class="badge badge-{{ $response->status === 'submitted' ? 'success' : 'secondary' }}">
                                {{ ucfirst($response->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($responses->hasPages())
                <div class="mt-3">
                    {{ $responses->links() }}
                </div>
                @endif
                @else
                <p class="text-muted text-center">{{ __('No responses yet') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

