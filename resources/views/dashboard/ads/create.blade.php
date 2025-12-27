@extends('dashboard.layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Create Advertisement</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.ads.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Ad Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Destination Link</label>
                        <input type="url" name="link" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ad Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('dashboard.ads.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Ad</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
