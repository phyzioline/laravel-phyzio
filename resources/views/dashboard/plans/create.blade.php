@extends('dashboard.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Create Treatment Plan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.plans.store') }}" method="POST">
                    @csrf
                    <!-- Simplified form for demo purposes -->
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">Select Patient</option>
                            @foreach(\App\Models\Patient::take(20)->get() as $p)
                                <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Diagnosis / Condition</label>
                        <input type="text" name="diagnosis" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Details</label>
                        <textarea name="goals" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('dashboard.plans.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
