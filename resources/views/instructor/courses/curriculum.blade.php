@extends('web.layouts.dashboard_master')

@section('title', 'Build Curriculum: ' . $course->title)
@section('header_title', 'Build Curriculum')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('instructor.courses.edit', $course->id) }}">1. Course Basics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">2. Curriculum & Cases</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link disabled" href="#">3. Publish</a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-primary mb-0">Course Modules</h5>
                    <button class="btn btn-outline-primary" data-toggle="modal" data-target="#addModuleModal"><i class="las la-plus"></i> Add New Module</button>
                </div>

                <div class="accordion" id="curriculumAccordion">
                    @forelse($course->modules as $module)
                    <div class="card mb-3 border">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center" id="heading{{ $module->id }}">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left text-dark font-weight-bold collapsed" type="button" data-toggle="collapse" data-target="#collapse{{ $module->id }}">
                                    <i class="las la-folder mr-2"></i> {{ $module->title }}
                                </button>
                            </h2>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light" data-toggle="modal" data-target="#addUnitModal{{ $module->id }}"><i class="las la-plus-circle"></i> Add Unit</button>
                                <button class="btn btn-sm btn-light text-danger"><i class="las la-trash"></i></button>
                            </div>
                        </div>

                        <div id="collapse{{ $module->id }}" class="collapse" data-parent="#curriculumAccordion">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    @forelse($module->units as $unit)
                                    <li class="list-group-item d-flex justify-content-between align-items-center pl-5">
                                        <div>
                                            @if($unit->unit_type == 'theory') <i class="las la-book text-info mr-2"></i>
                                            @elseif($unit->unit_type == 'demo') <i class="las la-play-circle text-success mr-2"></i>
                                            @elseif($unit->unit_type == 'case') <i class="las la-user-injured text-warning mr-2"></i>
                                            @elseif($unit->unit_type == 'assessment') <i class="las la-question-circle text-danger mr-2"></i>
                                            @endif
                                            {{ $unit->title }}
                                            <small class="text-muted ml-2">({{ $unit->duration_minutes }} min)</small>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-light"><i class="las la-edit"></i></button>
                                        </div>
                                    </li>
                                    @empty
                                    <li class="list-group-item text-center text-muted py-3">No units yet. click 'Add Unit' to start.</li>
                                    @endforelse
                                </ul>

                                <!-- Add Unit Modal -->
                                <div class="modal fade" id="addUnitModal{{ $module->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('instructor.courses.modules.units.store', [$course->id, $module->id]) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Unit to: {{ $module->title }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Unit Title</label>
                                                        <input type="text" name="title" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Type</label>
                                                        <select name="unit_type" class="form-control">
                                                            <option value="theory">Theory / Reading</option>
                                                            <option value="demo">Video Demonstration</option>
                                                            <option value="case">Clinical Case Study</option>
                                                            <option value="assessment">Quiz / Assessment</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Duration (Minutes)</label>
                                                        <input type="number" name="duration_minutes" class="form-control" value="10">
                                                    </div>
                                                    {{-- Detailed content fields would be shown dynamically via JS in a real app --}}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Add Unit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 border bg-light rounded">
                        <h4>No Modules yet</h4>
                        <p>Create your first module to organize your course content.</p>
                        <button class="btn btn-primary mt-2" data-toggle="modal" data-target="#addModuleModal">Create First Module</button>
                    </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-between mt-4">
                     <a href="{{ route('instructor.courses.edit', $course->id) }}" class="btn btn-light"><i class="las la-arrow-left"></i> Back to Basics</a>
                     <form action="{{ route('instructor.courses.update', $course->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="published">
                        <button type="submit" class="btn btn-success btn-lg">Publish Course <i class="las la-check"></i></button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Add Module Modal -->
<div class="modal fade" id="addModuleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('instructor.courses.modules.store', $course->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create New Module</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Module Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Introduction to Anatomy" required>
                    </div>
                </div>
                <div class="modal-footer">
                     <button type="submit" class="btn btn-primary">Create Module</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
