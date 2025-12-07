@extends('dashboard.layouts.app')
@section('title', __('Users'))

@section('content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <h6 class="mb-0 text-uppercase">User</h6>
            <hr>
            @if($users->total() > 0)
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle"></i> 
                    {{ __('Showing :from-:to of :total users. Admin users are excluded from this list.', [
                        'from' => $users->firstItem() ?? 0,
                        'to' => $users->lastItem() ?? 0,
                        'total' => $users->total()
                    ]) }}
                </div>
            @endif
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" style="width:100%">

                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Joined Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>
                                            @if($user->type)
                                                {{ $user->type }}
                                            @elseif($user->hasRole('therapist'))
                                                therapist
                                            @elseif($user->hasRole('admin'))
                                                admin
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $user->status }}</td>
                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            @can('users-delete')
                                                <button type="button" class="btn btn-danger delete-country-btn"
                                                    data-id="{{ $user->id }}">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            @endcan
                                            @can('users-update')
                                                <a href="{{ route('dashboard.users.edit', $user) }}"
                                                    class="btn btn-info"><i class="fas fa-edit"></i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">{{ __('Nothing!') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                    @if($users->hasPages())
                        <div class="card-footer">
                            <div style="padding:5px;direction: ltr;">
                                {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>
    <!--end main wrapper-->
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.delete-country-btn').forEach(button => {
                button.addEventListener('click', function() {
                    let id = this.getAttribute('data-id');

                    Swal.fire({
                        title: '{{ __('Are you sure?') }}',
                        text: "{{ __('Do you want to delete this item') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DC143C',
                        cancelButtonColor: '#696969',
                        cancelButtonText: "{{ __('Cancel') }}",
                        confirmButtonText: '{{ __('Yes, delete it!') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let form = document.createElement('form');
                            form.action = '{{ url('/dashboard/users') }}/' + id;
                            form.method = 'POST';
                            form.style.display = 'none';

                            let csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';

                            let methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';

                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
