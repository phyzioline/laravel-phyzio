@extends('dashboard.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Exercise</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.exercises.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Exercise Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Target Muscle</label>
                        <input type="text" name="target_muscle_group" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Difficulty</label>
                        <select name="difficulty_level" class="form-select">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Instructions</label>
                        <textarea name="instructions" class="form-control" rows="5"></textarea>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('dashboard.exercises.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Exercise</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
