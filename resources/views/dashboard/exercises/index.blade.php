@extends('dashboard.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Exercise Library</h5>
                <a href="{{ route('dashboard.exercises.create') }}" class="btn btn-primary btn-sm">Add Exercise</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Difficulty</th>
                                <th>Target Muscle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exercises as $exercise)
                            <tr>
                                <td>{{ $exercise->id }}</td>
                                <td>{{ $exercise->name }}</td>
                                <td>{{ $exercise->difficulty_level }}</td>
                                <td>{{ $exercise->target_muscle_group }}</td>
                                <td>
                                    <a href="{{ route('dashboard.exercises.edit', $exercise->id) }}" class="btn btn-sm btn-info">Edit</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No exercises found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $exercises->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
