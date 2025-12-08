@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Points</h1>
        <a href="{{ route('dashboard.data_points.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-teal shadow-sm" style="background-color: #0d9488; color: white;">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Data Point
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Data Points</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Country</th>
                            <th>Year</th>
                            <th>Total PTs</th>
                            <th>Avg Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data_points as $point)
                        <tr>
                            <td>{{ $point->id }}</td>
                            <td>{{ $point->country }}</td>
                            <td>{{$point->year }}</td>
                            <td>{{ number_format($point->total_therapists) }}</td>
                            <td>${{ number_format($point->average_salary, 2) }}</td>
                            <td>
                                <a href="{{ route('dashboard.data_points.edit', $point->id) }}" class="btn btn-info btn-sm">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
