@extends('therapist.layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-xl-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">Availability Settings</h5>
                <p class="text-muted small">Set your available working hours for each day.</p>
                
                <form action="{{ route('therapist.availability.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        @php
                            $lowerDay = strtolower($day);
                            $isActive = isset($availability[$lowerDay]) && ($availability[$lowerDay]['active'] ?? false);
                            $start = $availability[$lowerDay]['start'] ?? '09:00';
                            $end = $availability[$lowerDay]['end'] ?? '17:00';
                        @endphp
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="availability[{{ $lowerDay }}][active]" value="1" id="check_{{ $lowerDay }}" {{ $isActive ? 'checked' : '' }}>
                                    <label class="form-check-label" for="check_{{ $lowerDay }}">
                                        {{ $day }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <input type="time" name="availability[{{ $lowerDay }}][start]" class="form-control" value="{{ $start }}">
                            </div>
                            <div class="col-md-1 text-center">to</div>
                            <div class="col-md-4">
                                <input type="time" name="availability[{{ $lowerDay }}][end]" class="form-control" value="{{ $end }}">
                            </div>
                        </div>
                    @endforeach

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Save Availability</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
