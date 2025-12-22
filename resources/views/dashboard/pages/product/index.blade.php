@extends('dashboard.layouts.app')
@section('title', __('Products'))

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <style>
        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
            border-radius: 4px;
            padding: 0.375rem 0.75rem;
        }
        tfoot input, tfoot select {
            width: 100%;
            padding: 4px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        tfoot th {
            padding: 8px;
        }
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 15px;
            text-align: center;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
            margin: 0 2px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            cursor: pointer;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #0d6efd;
            color: white !important;
            border-color: #0d6efd;
        }
        .dataTables_wrapper .dataTables_info {
            padding-top: 15px;
        }
    </style>
@endpush

@section('content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row mb-5">
                <div class="col-12 col-xl-12">
                    <div class="card">
                        <div class="add d-flex justify-content-end p-2 gap-2">
                             <!-- Export Dropdown -->
                             <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-file-export"></i> {{ __('Export') }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('dashboard.products.export', 'csv') }}">CSV</a></li>
                                    <li><a class="dropdown-item" href="{{ route('dashboard.products.export', 'xlsx') }}">Excel</a></li>
                                    <li><a class="dropdown-item" href="{{ route('dashboard.products.export', 'xml') }}">XML</a></li>
                                </ul>
                            </div>

                            <!-- Import Button -->
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="fas fa-file-import"></i> {{ __('Import') }}
                            </button>

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
                                                <td>{{ $product->id }}</td>
                                                <td>
                                                    <div style="width: 80px; height: 80px; overflow: hidden; border-radius: 8px; border: 1px solid #eee; display: flex; align-items: center; justify-content: center; background: #fff;">
                                                        <img src="{{ asset($product->productImages->first()?->image) }}"
                                                            alt="Product"
                                                            style="width: 100%; height: 100%; object-fit: contain;">
                                                    </div>
                                                </td>
                                                <td>{{ $product->category?->{'name_' . app()->getLocale()} }}</td>
                                                <td>{{ $product->sub_category?->{'name_' . app()->getLocale()} }}</td>
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
                                    <tfoot>
                                    </tfoot>
                                </table>
                                <!-- Pagination handled by DataTables -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--end main wrapper-->
    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">{{ __('Import Products') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dashboard.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @role('admin')
                        <div class="mb-3">
                            <label for="vendor_email" class="form-label">{{ __('Vendor Email (Optional)') }}</label>
                            <input class="form-control" type="email" id="vendor_email" name="vendor_email" placeholder="e.g. phyzioline@gmail.com">
                            <div class="form-text">{{ __('Leave empty to import for yourself.') }}</div>
                        </div>
                        @endrole
                        <div class="mb-3">
                            <label for="file" class="form-label">{{ __('Choose File (CSV, XML, Excel)') }}</label>
                            <input class="form-control" type="file" id="file" name="file" required accept=".csv,.xml,.txt,.xlsx,.xls">
                            <div class="form-text">
                                {{ __('Supported formats: CSV, XML, Excel (.xlsx, .xls)') }}
                                <br>
                                <a href="{{ route('dashboard.products.export', 'csv') }}" class="text-primary">{{ __('Download CSV Template') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Upload & Import') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Check if SweetAlert2 is loaded
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 is not loaded!');
                alert('Error: SweetAlert2 library is missing. Please refresh the page.');
                return;
            }
            
            console.log('jQuery and SweetAlert2 loaded successfully');
            
            // Initialize DataTable
            var table = $('#example2').DataTable({
                responsive: true,
                order: [[0, 'asc']],
                pageLength: 10,
                paging: true,
                paginationType: 'full_numbers',
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                columnDefs: [
                    { orderable: false, targets: [1, 9] }, // Image and Actions columns not sortable
                ],
                language: {
                    @if(app()->getLocale() == 'ar')
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json'
                    @endif
                },
                initComplete: function () {
                    // Add column filters
                    this.api().columns().every(function (index) {
                        var column = this;
                        var title = $(column.header()).text();
                        
                        // Skip Image and Actions columns
                        if (index === 1 || index === 9) {
                            $(column.footer()).html('');
                            return;
                        }
                        
                        // For Category and Sub Category columns, use select dropdown
                        if (index === 2 || index === 3) {
                            var select = $('<select><option value="">{{ __('All') }}</option></select>')
                                .appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>');
                            });
                            return;
                        }
                        
                        // For Status column, use select dropdown
                        if (index === 8) {
                            var select = $('<select><option value="">{{ __('All') }}</option></select>')
                                .appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>');
                            });
                            return;
                        }
                        
                        // For other columns, use text input
                        var input = $('<input type="text" placeholder="{{ __('Search') }} ' + title + '" />')
                            .appendTo($(column.footer()).empty())
                            .on('keyup change clear', function () {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                    });
                }
            });

            // Delete button functionality - Enhanced with better event handling
            $(document).on('click', '.delete-country-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                let id = $(this).data('id');
                console.log('Delete button clicked for product ID:', id);

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
                        console.log('Deletion confirmed, submitting form...');
                        
                        // Create and submit form
                        let form = $('<form>', {
                            'action': '{{ route("dashboard.products.index") }}/' + id,
                            'method': 'POST',
                            'style': 'display:none'
                        });

                        form.append($('<input>', {'type': 'hidden', 'name': '_token', 'value': '{{ csrf_token() }}'}));
                        form.append($('<input>', {'type': 'hidden', 'name': '_method', 'value': 'DELETE'}));
                        
                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
