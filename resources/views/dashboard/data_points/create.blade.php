@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add New Data Point</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('dashboard.data_points.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" name="country" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Year</label>
                            <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Total Therapists</label>
                            <input type="number" name="total_therapists" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Average Salary (USD)</label>
                            <input type="number" name="average_salary" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Employment Rate (%)</label>
                            <input type="number" name="employment_rate" class="form-control" step="0.01" min="0" max="100">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create Data Point</button>
                <a href="{{ route('dashboard.data_points.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
