@extends('web.layouts.dashboard_master')

@section('title', 'New ' . ucfirst($episode->specialty) . ' Assessment')
@section('header_title', 'New Assessment: ' . ucfirst($episode->specialty))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                Specialty Mode: <strong>{{ ucfirst($episode->specialty) }}</strong>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('clinic.episodes.assessments.store', $episode->id) }}" method="POST">
                    @csrf
                    
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label>Date</label>
                            <input type="date" name="assessment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option value="initial">Initial Evaluation</option>
                                <option value="re_eval">Re-Evaluation</option>
                                <option value="discharge">Discharge Summary</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <!-- Dynamic Objective Section based on Schema -->
                    <h5 class="text-primary mb-3">Objective Findings</h5>
                    <div class="row">
                        @foreach($schema['assessment_fields'] as $field)
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">{{ ucwords(str_replace('_', ' ', $field)) }}</label>
                            <input type="text" name="objective[{{ $field }}]" class="form-control" placeholder="Enter findings...">
                        </div>
                        @endforeach
                    </div>

                    <hr>
                    
                    <h5 class="text-primary mb-3">Red Flags Check</h5>
                     <div class="form-group">
                         <div class="alert alert-warning">
                             <strong>Screen for:</strong> {{ implode(', ', array_map(function($i){ return str_replace('_', ' ', $i); }, $schema['red_flags'])) }}
                         </div>
                         <div class="custom-control custom-checkbox">
                             <input type="checkbox" name="red_flags_detected" class="custom-control-input" id="redFlags">
                             <label class="custom-control-label" for="redFlags">Yes, Red Flags Detected (Requires Escalation)</label>
                         </div>
                     </div>
                     
                     <hr>

                    <div class="form-group">
                        <label>Clinical Analysis / Assessment</label>
                        <textarea name="analysis" class="form-control" rows="4" placeholder="Summarize your findings and clinical reasoning..."></textarea>
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('clinic.episodes.show', $episode->id) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">Save Assessment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
