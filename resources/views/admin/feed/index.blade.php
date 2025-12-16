@extends('web.layouts.dashboard_master')

@section('title', 'Feed Management')
@section('header_title', 'Engagement Feed')

@section('content')
<div class="row">
    <!-- Quick Stats -->
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                 <h4 class="mb-0">Content Feed</h4>
                 <p class="text-muted">Manage what users see in their daily feed.</p>
            </div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createFeedModal"><i class="las la-plus"></i> Post Update</button>
        </div>
    </div>

    <!-- Feed Table -->
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Content</th>
                            <th>Type</th>
                            <th>Target Audience</th>
                            <th>Engagement</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td style="max-width: 300px;">
                                <div class="d-flex align-items-center">
                                    @if($item->media_url)
                                        <img src="{{ $item->media_url }}" class="rounded mr-3" width="50" height="50" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="las la-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $item->title }}</strong>
                                        <div class="text-muted small">{{ Str::limit($item->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-light border">{{ ucfirst($item->type) }}</span></td>
                            <td>
                                @foreach($item->target_audience ?? [] as $aud)
                                    <span class="badge badge-info">{{ ucfirst($aud) }}</span>
                                @endforeach
                            </td>
                            <td>
                                <div class="d-flex align-items-center small text-muted">
                                    <span class="mr-2"><i class="las la-eye"></i> {{ $item->views_count }}</span>
                                    <span class="mr-2"><i class="las la-mouse-pointer"></i> {{ $item->clicks_count }}</span>
                                    <span><i class="las la-thumbs-up"></i> {{ $item->likes_count }}</span>
                                </div>
                            </td>
                            <td>
                                @if($item->status == 'active') <span class="badge badge-success">Active</span>
                                @else <span class="badge badge-secondary">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.feed.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this post?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="las la-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4">No feed items found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createFeedModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Feed Post</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.feed.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required placeholder="e.g. New 50% Off Sale">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option value="news">News / Tip</option>
                                <option value="promo">Promotion</option>
                                <option value="alert">System Alert</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Action Link (URL)</label>
                            <input type="url" name="action_link" class="form-control" required placeholder="https://...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Media URL (Image)</label>
                            <input type="text" name="media_url" class="form-control" placeholder="Image link...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Target Audience</label> <br>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="target_audience[]" value="all" class="custom-control-input" id="audAll" checked>
                            <label class="custom-control-label" for="audAll">All Users</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                             <input type="checkbox" name="target_audience[]" value="therapist" class="custom-control-input" id="audTherapist">
                             <label class="custom-control-label" for="audTherapist">Therapists</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Publish Now</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
