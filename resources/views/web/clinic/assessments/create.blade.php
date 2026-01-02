@extends('web.layouts.dashboard_master')

@section('title', __('New Assessment'))
@section('header_title', __('Clinical Assessment'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;" data-specialty="{{ $specialty ?? 'orthopedic' }}">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title font-weight-bold mb-0">
                            @if($template)
                                {{ $template->name }}
                            @else
                                {{ __('New Assessment') }} - {{ ucfirst($specialty ?? 'orthopedic') }}
                            @endif
                        </h5>
                        <p class="text-muted mb-0 small">
                            {{ __('Episode') }}: {{ $episode->title ?? 'N/A' }}
                            @if($previousAssessment)
                                | {{ __('Previous') }}: {{ $previousAssessment->assessment_date->format('M d, Y') }}
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('clinic.episodes.show', $episode) }}" class="btn btn-secondary">
                        <i class="las la-times"></i> {{ __('Cancel') }}
                    </a>
                </div>
            </div>
            <div class="card-body px-4">
                <form action="{{ route('clinic.episodes.assessments.store', $episode->id) }}" method="POST" id="assessmentForm"
                      data-inline-validation="true">
                    @csrf
                    @if($template)
                        <input type="hidden" name="template_id" value="{{ $template->id }}">
                    @endif
                    <input type="hidden" name="specialty" value="{{ $specialty ?? 'orthopedic' }}">
                    
                    <!-- Assessment Date & Type -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Assessment Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="assessment_date" class="form-control" 
                                   value="{{ old('assessment_date', date('Y-m-d')) }}" 
                                   max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Assessment Type') }} <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" required>
                                <option value="initial" {{ old('type') == 'initial' ? 'selected' : '' }}>{{ __('Initial Evaluation') }}</option>
                                <option value="re_eval" {{ old('type', 're_eval') == 're_eval' ? 'selected' : '' }}>{{ __('Re-Evaluation') }}</option>
                                <option value="discharge" {{ old('type') == 'discharge' ? 'selected' : '' }}>{{ __('Discharge Summary') }}</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Subjective Assessment -->
                    <h5 class="font-weight-bold mb-3" style="color: #00897b;">
                        <i class="las la-comment-medical"></i> {{ __('Subjective Assessment') }}
                    </h5>
                    
                    @if($template && isset($template->subjective_fields))
                        @foreach($template->subjective_fields as $field)
                        <div class="form-group mb-3">
                            <label class="form-label">
                                {{ $field['label'] }}
                                @if(isset($field['required']) && $field['required'])
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            
                            @if($field['type'] === 'select')
                                <select name="subjective[{{ $field['label'] }}]" 
                                        class="form-control" 
                                        {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
                                    <option value="">{{ __('Select') }}...</option>
                                    @foreach($field['options'] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            @elseif($field['type'] === 'multiselect')
                                <select name="subjective[{{ $field['label'] }}][]" 
                                        class="form-control" 
                                        multiple
                                        {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
                                    @foreach($field['options'] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">{{ __('Hold Ctrl/Cmd to select multiple') }}</small>
                            @elseif($field['type'] === 'textarea')
                                <textarea name="subjective[{{ $field['label'] }}]" 
                                          class="form-control" 
                                          rows="3"
                                          placeholder="{{ $field['placeholder'] ?? '' }}"
                                          {{ isset($field['required']) && $field['required'] ? 'required' : '' }}></textarea>
                            @elseif($field['type'] === 'date')
                                <input type="date" 
                                       name="subjective[{{ $field['label'] }}]" 
                                       class="form-control"
                                       {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
                            @else
                                <input type="text" 
                                       name="subjective[{{ $field['label'] }}]" 
                                       class="form-control"
                                       placeholder="{{ $field['placeholder'] ?? '' }}"
                                       {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="form-group">
                            <label class="form-label">{{ __('Chief Complaint') }}</label>
                            <textarea name="subjective[chief_complaint]" class="form-control" rows="3" 
                                      placeholder="{{ __('Patient\'s main concern...') }}"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('History of Present Illness') }}</label>
                            <textarea name="subjective[history]" class="form-control" rows="4" 
                                      placeholder="{{ __('Detailed history...') }}"></textarea>
                        </div>
                    @endif

                    <hr class="my-4">

                    <!-- Pain Scale (if template has pain scale config) -->
                    @if($template && isset($template->pain_scale_config))
                    <h5 class="font-weight-bold mb-3" style="color: #00897b;">
                        <i class="las la-heartbeat"></i> {{ __('Pain Assessment') }}
                    </h5>
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <label class="form-label font-weight-bold">{{ __('Current Pain Level') }}</label>
                            <div class="pain-scale-container">
                                <input type="range" 
                                       name="objective[pain_level]" 
                                       id="painScale" 
                                       class="form-control-range pain-slider"
                                       min="{{ $template->pain_scale_config['min'] ?? 0 }}"
                                       max="{{ $template->pain_scale_config['max'] ?? 10 }}"
                                       step="0.5"
                                       value="0">
                                <div class="d-flex justify-content-between mt-2">
                                    @if(isset($template->pain_scale_config['labels']))
                                        @foreach($template->pain_scale_config['labels'] as $label)
                                            <small class="text-muted">{{ $label }}</small>
                                        @endforeach
                                    @else
                                        <small class="text-muted">0 - No Pain</small>
                                        <small class="text-muted">5 - Moderate</small>
                                        <small class="text-muted">10 - Worst Possible</small>
                                    @endif
                                </div>
                                <div class="text-center mt-3">
                                    <h3 class="mb-0" id="painValue" style="color: #00897b; font-weight: bold;">0</h3>
                                    <small class="text-muted">{{ __('Current Pain Level') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <hr class="my-4">

                    <!-- Objective Assessment -->
                    <h5 class="font-weight-bold mb-3" style="color: #00897b;">
                        <i class="las la-stethoscope"></i> {{ __('Objective Assessment') }}
                    </h5>
                    
                    @if($template && isset($template->objective_fields))
                        @foreach($template->objective_fields as $field)
                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">
                                {{ $field['label'] }}
                                @if(isset($field['required']) && $field['required'])
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            
                            @if($field['type'] === 'rom_slider')
                                <div class="rom-slider-container card bg-light p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="small text-muted">{{ __('Measured Value') }} ({{ $field['unit'] ?? 'degrees' }})</label>
                                            <input type="range" 
                                                   name="objective[{{ $field['label'] }}]" 
                                                   class="form-control-range rom-slider"
                                                   data-joint="{{ $field['joint'] }}"
                                                   data-movement="{{ $field['movement'] }}"
                                                   data-normal-min="{{ $field['normal_range'][0] ?? 0 }}"
                                                   data-normal-max="{{ $field['normal_range'][1] ?? 180 }}"
                                                   min="0"
                                                   max="{{ $field['normal_range'][1] ?? 180 }}"
                                                   step="1"
                                                   value="0">
                                            <div class="d-flex justify-content-between mt-2">
                                                <small class="text-muted">0°</small>
                                                <small class="text-muted">{{ $field['normal_range'][1] ?? 180 }}°</small>
                                            </div>
                                            <div class="text-center mt-2">
                                                <h4 class="mb-0 rom-value" style="color: #00897b; font-weight: bold;">0°</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="rom-comparison">
                                                <label class="small text-muted">{{ __('Normal Range') }}</label>
                                                <div class="progress" style="height: 30px;">
                                                    <div class="progress-bar bg-success" 
                                                         style="width: {{ (($field['normal_range'][1] - $field['normal_range'][0]) / ($field['normal_range'][1] ?? 180)) * 100 }}%">
                                                        {{ $field['normal_range'][0] }}° - {{ $field['normal_range'][1] }}°
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-muted" id="romStatus-{{ $loop->index }}"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($field['type'] === 'strength_grade')
                                <select name="objective[{{ $field['label'] }}]" class="form-control">
                                    <option value="">{{ __('Select Grade') }}</option>
                                    @for($i = 0; $i <= 5; $i++)
                                        <option value="{{ $i }}/5">{{ $i }}/5 - 
                                            @if($i == 0) {{ __('No contraction') }}
                                            @elseif($i == 1) {{ __('Trace') }}
                                            @elseif($i == 2) {{ __('Poor') }}
                                            @elseif($i == 3) {{ __('Fair') }}
                                            @elseif($i == 4) {{ __('Good') }}
                                            @else {{ __('Normal') }}
                                            @endif
                                        </option>
                                    @endfor
                                </select>
                            @else
                                <input type="text" 
                                       name="objective[{{ $field['label'] }}]" 
                                       class="form-control"
                                       {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="form-group">
                            <label class="form-label">{{ __('Objective Findings') }}</label>
                            <textarea name="objective[findings]" class="form-control" rows="5" 
                                      placeholder="{{ __('Document your objective findings...') }}"></textarea>
                        </div>
                    @endif

                    <!-- Special Tests -->
                    @if($template && isset($template->special_tests))
                    <hr class="my-4">
                    <h5 class="font-weight-bold mb-3" style="color: #00897b;">
                        <i class="las la-vial"></i> {{ __('Special Tests') }}
                    </h5>
                    @foreach($template->special_tests as $test)
                    <div class="form-group mb-3">
                        <label class="form-label">{{ $test['name'] }}</label>
                        <select name="special_tests[{{ $test['name'] }}]" class="form-control">
                            <option value="">{{ __('Not Performed') }}</option>
                            @foreach($test['result_options'] as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endforeach
                    @endif

                    <hr class="my-4">

                    <!-- Clinical Analysis -->
                    <div class="form-group">
                        <label class="form-label font-weight-bold">{{ __('Clinical Analysis / Assessment') }}</label>
                        <textarea name="analysis" class="form-control" rows="5" 
                                  placeholder="{{ __('Summarize your findings and clinical reasoning...') }}">{{ old('analysis') }}</textarea>
                    </div>

                    <!-- Red Flags -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="red_flags_detected" class="custom-control-input" id="redFlags" value="1">
                            <label class="custom-control-label" for="redFlags">
                                <strong class="text-danger">{{ __('Red Flags Detected') }}</strong> 
                                <small class="text-muted">({{ __('Requires immediate attention') }})</small>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('clinic.episodes.show', $episode) }}" class="btn btn-secondary">
                            <i class="las la-times"></i> {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-5" style="background-color: #00897b;">
                            <i class="las la-save"></i> {{ __('Save Assessment') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .pain-slider {
        width: 100%;
        height: 40px;
        -webkit-appearance: none;
        appearance: none;
        background: linear-gradient(to right, #4caf50 0%, #ffeb3b 50%, #f44336 100%);
        outline: none;
        border-radius: 20px;
    }
    
    .pain-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 30px;
        height: 30px;
        background: #00897b;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }
    
    .pain-slider::-moz-range-thumb {
        width: 30px;
        height: 30px;
        background: #00897b;
        border-radius: 50%;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }
    
    .rom-slider {
        width: 100%;
        height: 30px;
        -webkit-appearance: none;
        appearance: none;
        background: #e0e0e0;
        outline: none;
        border-radius: 15px;
    }
    
    .rom-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 25px;
        height: 25px;
        background: #00897b;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .rom-slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        background: #00897b;
        border-radius: 50%;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .rom-slider-container {
        border-left: 4px solid #00897b;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/specialty-assessment-forms.js') }}"></script>
<script>
<script src="{{ asset('js/clinic-form-enhancements.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pain Scale Slider
    const painScale = document.getElementById('painScale');
    const painValue = document.getElementById('painValue');
    
    if (painScale && painValue) {
        painScale.addEventListener('input', function() {
            const value = parseFloat(this.value);
            painValue.textContent = value.toFixed(1);
            
            // Update color based on pain level
            if (value <= 3) {
                painValue.style.color = '#4caf50'; // Green
            } else if (value <= 6) {
                painValue.style.color = '#ff9800'; // Orange
            } else {
                painValue.style.color = '#f44336'; // Red
            }
        });
    }
    
    // ROM Sliders
    const romSliders = document.querySelectorAll('.rom-slider');
    romSliders.forEach((slider, index) => {
        const valueDisplay = slider.closest('.rom-slider-container').querySelector('.rom-value');
        const statusDisplay = document.getElementById('romStatus-' + index);
        const normalMin = parseFloat(slider.dataset.normalMin);
        const normalMax = parseFloat(slider.dataset.normalMax);
        
        slider.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (valueDisplay) {
                valueDisplay.textContent = value + '°';
            }
            
            // Compare to normal range
            if (statusDisplay) {
                if (value < normalMin) {
                    statusDisplay.textContent = '⚠️ Below normal range';
                    statusDisplay.className = 'text-warning';
                } else if (value > normalMax) {
                    statusDisplay.textContent = '⚠️ Above normal range';
                    statusDisplay.className = 'text-warning';
                } else {
                    statusDisplay.textContent = '✅ Within normal range';
                    statusDisplay.className = 'text-success';
                }
            }
        });
    });
});
</script>
@endpush
@endsection

