@extends('web.therapist.layout')

@section('header_title', 'Manage Availability')

@section('content')
<div class="card-box">
    <p>Set your available working hours for appointments.</p>
    <form action="{{ route('therapist.availability.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Working Days</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="days[]" value="Monday" checked>
                <label class="form-check-label">Monday</label>
            </div>
            <!-- Add other days -->
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save Availability</button>
    </form>
</div>
@endsection
