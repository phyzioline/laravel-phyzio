@extends('dashboard.layouts.app')
@section('title', __('Roles'))

@section('content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row mb-5">
                <div class="col-12 col-xl-12">
                    <div class="card">
                        {{-- <div class="add d-flex justify-content-end p-2">
                            @can('orders-create')
                                <a href="{{ route('dashboard.orders.create') }}" class="btn btn-primary">
                                    <i class="fas fa-add"></i> {{ __('Add order') }}
                                </a>
                            @endcan
                        </div> --}}
                        <div class="card-body">
                            <!-- Order Tabs -->
                            <ul class="nav nav-tabs mb-4">
                                <li class="nav-item">
                                    <a class="nav-link {{ !request('status') && !request('payment_method') ? 'active' : '' }}" href="{{ route('dashboard.orders.index') }}">
                                        {{ __('All Orders') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('dashboard.orders.index', ['status' => 'pending']) }}">
                                        {{ __('Pending') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" href="{{ route('dashboard.orders.index', ['status' => 'completed']) }}">
                                        {{ __('Shipped') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status') == 'cancelled' ? 'active' : '' }}" href="{{ route('dashboard.orders.index', ['status' => 'cancelled']) }}">
                                        {{ __('Cancelled') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('payment_method') == 'cash' ? 'active' : '' }}" href="{{ route('dashboard.orders.index', ['payment_method' => 'cash']) }}">
                                        {{ __('Cash Orders') }}
                                    </a>
                                </li>
                            </ul>

                            <div class="table-responsive text-center">
                                <table id="example2" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            @if(auth()->user()->hasRole('admin'))
                                                <th>{{ __('Vendor') }}</th>
                                            @endif
                                            <th>{{ __('Name Buyer') }}</th>
                                            <th>{{ __('Phone Buyer') }}</th>
                                            <th>{{ __('Address Buyer') }}</th>
                                            <th>{{ __('Total Price') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Payment Status') }}</th>
                                            <th>{{ __('Payment Method') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $order)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                 @if(auth()->user()->hasRole('admin'))
                                                <td>{{ $order->items->first()?->product?->user?->name }}</td>

                                            @endif
                                                <td>{{ $order->name }}</td>
                                                <td>{{ $order->phone }}</td>
                                                <td>{{ $order->address }}</td>
                                                <td>{{ $order->total }}</td>
                                                <td>{{ $order->status }}</td>
                                                <td>{{ $order->payment_status }}</td>
                                                <td>{{ $order->payment_method }}</td>

                                                <td>
                                                    @can('orders-delete')
                                                        <button type="button" class="btn btn-danger w-25 delete-country-btn"
                                                            data-id="{{ $order->id }}">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    @endcan
                                                    @can('orders-show')
                                                        <a href="{{ route('dashboard.orders.show', $order) }}"
                                                            class="btn btn-warning  w-25">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan
                                                    @can('orders-update')
                                                        <a href="{{ route('dashboard.orders.edit', $order) }}"
                                                            class="btn btn-info w-25">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">{{ __('No data available!') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div style="padding:5px;direction: ltr;">
                                    {!! $data->withQueryString()->links('pagination::bootstrap-5') !!}
                                </div>
                            </div>
                        </div>
                    </div>
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
                            form.action = '{{ url('/dashboard/orders') }}/' + id;
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
