@extends('dashboard.layouts.app')
@section('title', __('Roles'))

@section('content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row mb-5">
                <div class="col-12 col-xl-12">
                    <div class="card">
                        <div class="add d-flex justify-content-end p-2">
                            @can('products-create')
                                <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary">
                                    <i class="fas fa-add"></i> {{ __('Add Product') }}
                                </a>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive text-center">
                                <table id="example2" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Image') }}</th>
                                            <th>{{ __('Category') }}</th>
                                            <th>{{ __('Sub Category') }}</th>
                                            <th>{{ __('Product Name') }}</th>
                                            <th>{{ __('Price') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('SKU') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $product)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>
                                                    <img src="{{ asset($product->productImages->first()?->image) }}"
                                                        alt="صورة المنتج" width="80">
                                                </td>
                                                <td>{{ $product->category->{'name_' . app()->getLocale()} }}</td>
                                                <td>{{ $product->sub_category->{'name_' . app()->getLocale()} }}</td>
                                                <td>{{ $product->{'product_name_' . app()->getLocale()} }}</td>
                                                <td>{{ $product->product_price }}</td>
                                                <td>{{ $product->amount }}</td>
                                                <td>{{ $product->sku }}</td>
                                                <td>{{ $product->status }}</td>
                                                <td>
                                                    @can('products-delete')
                                                        <button type="button" class="btn btn-danger w-25 delete-country-btn"
                                                            data-id="{{ $product->id }}">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    @endcan
                                                    @can('products-show')
                                                        <a href="{{ route('dashboard.products.show', $product) }}"
                                                            class="btn btn-warning w-25">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan

                                                    @can('products-update')
                                                        <a href="{{ route('dashboard.products.edit', $product) }}"
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
                            form.action = '{{ url('/dashboard/products') }}/' + id;
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
