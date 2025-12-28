@extends('web.layouts.dashboard_master')

@section('title', 'Intake Forms')
@section('header_title', 'Intake Forms')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="font-weight-bold mb-0">{{ __('Intake Forms') }}</h5>
        <a href="{{ route('clinic.intake-forms.create') }}" class="btn btn-primary">
            <i class="las la-plus"></i> {{ __('Create Form') }}
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Fields') }}</th>
                        <th>{{ __('Required') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Responses') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forms ?? [] as $form)
                    <tr>
                        <td>
                            <strong>{{ $form->name }}</strong>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($form->description ?? 'No description', 50) }}</small>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ count($form->form_fields ?? []) }} {{ __('fields') }}</span>
                        </td>
                        <td>
                            @if($form->is_required)
                                <span class="badge badge-warning">{{ __('Required') }}</span>
                            @else
                                <span class="badge badge-secondary">{{ __('Optional') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($form->is_active)
                                <span class="badge badge-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge badge-secondary">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $form->responses()->count() }}</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $form->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('clinic.intake-forms.show', $form->id) }}" class="btn btn-sm btn-outline-primary" title="{{ __('View') }}">
                                <i class="las la-eye"></i>
                            </a>
                            <a href="{{ route('clinic.intake-forms.edit', $form->id) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="las la-file-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ __('No intake forms found') }}</p>
                            <a href="{{ route('clinic.intake-forms.create') }}" class="btn btn-primary">
                                <i class="las la-plus"></i> {{ __('Create First Form') }}
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($forms) && $forms->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $forms->links() }}
        </div>
        @endif
    </div>
</div>
@endif
@endsection

