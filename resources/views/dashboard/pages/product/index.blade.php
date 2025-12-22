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
        
        /* Amazon-style filters sidebar */
        .filters-sidebar {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .filter-section {
            border-bottom: 1px solid #e7e7e7;
            padding: 15px 0;
        }
        .filter-section:last-child {
            border-bottom: none;
        }
        .filter-section h6 {
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 14px;
            color: #111;
        }
        .filter-checkbox {
            margin: 8px 0;
            display: flex;
            align-items: center;
        }
        .filter-checkbox input[type="checkbox"] {
            margin-right: 8px;
            cursor: pointer;
        }
        .filter-checkbox label {
            cursor: pointer;
            font-size: 14px;
            color: #565959;
            margin: 0;
        }
        .filter-checkbox label:hover {
            color: #111;
        }
        .price-range-inputs {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .price-range-inputs input {
            flex: 1;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        /* Bulk actions toolbar */
        .bulk-actions-toolbar {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px 15px;
            margin-bottom: 15px;
            display: none;
            align-items: center;
            gap: 10px;
        }
        .bulk-actions-toolbar.active {
            display: flex;
        }
        .bulk-actions-toolbar .selected-count {
            font-weight: 600;
            color: #0d6efd;
        }
        .bulk-actions-toolbar .btn-group {
            margin-left: auto;
        }
        
        /* Checkbox column */
        .checkbox-column {
            width: 40px;
            text-align: center;
        }
        .checkbox-column input[type="checkbox"] {
            cursor: pointer;
            width: 18px;
            height: 18px;
        }
    </style>
@endpush

@section('content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row mb-5">
                <div class="col-12">
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
                        
                        <!-- Bulk Actions Toolbar -->
                        <div class="bulk-actions-toolbar" id="bulkActionsToolbar">
                            <span class="selected-count" id="selectedCount">0 {{ __('selected') }}</span>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-success" id="bulkActivate">
                                    <i class="fas fa-check"></i> {{ __('Activate') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-warning" id="bulkDeactivate">
                                    <i class="fas fa-times"></i> {{ __('Deactivate') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" id="bulkDelete">
                                    <i class="fas fa-trash"></i> {{ __('Delete') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-info" id="bulkExport">
                                    <i class="fas fa-download"></i> {{ __('Export') }}
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="row">
                                <!-- Filters Sidebar -->
                                <div class="col-md-3 mb-4">
                                    <div class="filters-sidebar">
                                        <h5 class="mb-3">{{ __('Filters') }}</h5>
                                        
                                        <!-- Status Filter -->
                                        <div class="filter-section">
                                            <h6>{{ __('Listing Status') }}</h6>
                                            <div class="filter-checkbox">
                                                <input type="checkbox" id="filter-status-active" class="filter-check" data-filter="status" value="active">
                                                <label for="filter-status-active">{{ __('Active') }}</label>
                                            </div>
                                            <div class="filter-checkbox">
                                                <input type="checkbox" id="filter-status-inactive" class="filter-check" data-filter="status" value="inactive">
                                                <label for="filter-status-inactive">{{ __('Inactive') }}</label>
                                            </div>
                                        </div>
                                        
                                        <!-- Stock Status Filter -->
                                        <div class="filter-section">
                                            <h6>{{ __('Stock Status') }}</h6>
                                            <div class="filter-checkbox">
                                                <input type="checkbox" id="filter-stock-in" class="filter-check" data-filter="stock" value="in">
                                                <label for="filter-stock-in">{{ __('In Stock') }} (>10)</label>
                                            </div>
                                            <div class="filter-checkbox">
                                                <input type="checkbox" id="filter-stock-low" class="filter-check" data-filter="stock" value="low">
                                                <label for="filter-stock-low">{{ __('Low Stock') }} (1-10)</label>
                                            </div>
                                            <div class="filter-checkbox">
                                                <input type="checkbox" id="filter-stock-out" class="filter-check" data-filter="stock" value="out">
                                                <label for="filter-stock-out">{{ __('Out of Stock') }}</label>
                                            </div>
                                        </div>
                                        
                                        <!-- Category Filter -->
                                        <div class="filter-section">
                                            <h6>{{ __('Category') }}</h6>
                                            @foreach($categories as $category)
                                            <div class="filter-checkbox">
                                                <input type="checkbox" id="filter-category-{{ $category->id }}" class="filter-check" data-filter="category" value="{{ $category->id }}">
                                                <label for="filter-category-{{ $category->id }}">{{ $category->{'name_' . app()->getLocale()} }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Price Range Filter -->
                                        <div class="filter-section">
                                            <h6>{{ __('Price Range') }}</h6>
                                            <div class="price-range-inputs">
                                                <input type="number" id="price-min" placeholder="{{ __('Min') }}" class="form-control form-control-sm">
                                                <input type="number" id="price-max" placeholder="{{ __('Max') }}" class="form-control form-control-sm">
                                            </div>
                                            <button type="button" class="btn btn-sm btn-primary mt-2 w-100" id="applyPriceFilter">{{ __('Apply') }}</button>
                                        </div>
                                        
                                        <!-- Clear Filters -->
                                        <div class="filter-section">
                                            <button type="button" class="btn btn-sm btn-outline-secondary w-100" id="clearFilters">{{ __('Clear All Filters') }}</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Products Table -->
                                <div class="col-md-9">
                                    <div class="table-responsive text-center">
                                        <table id="example2" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="checkbox-column">
                                                        <input type="checkbox" id="selectAll">
                                                    </th>
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
                                                    <tr data-product-id="{{ $product->id }}" 
                                                        data-status="{{ $product->status }}"
                                                        data-stock="{{ $product->amount }}"
                                                        data-category="{{ $product->category_id }}"
                                                        data-price="{{ $product->product_price }}">
                                                        <td class="checkbox-column">
                                                            <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                                                        </td>
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
                                                        <td>
                                                            <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                                                {{ $product->status }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @can('products-delete')
                                                                <button type="button" class="btn btn-danger btn-sm delete-country-btn"
                                                                    data-id="{{ $product->id }}">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </button>
                                                            @endcan
                                                            @can('products-show')
                                                                <a href="{{ route('dashboard.products.show', $product) }}"
                                                                    class="btn btn-warning btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endcan
                                                            @can('products-update')
                                                                <a href="{{ route('dashboard.products.edit', $product) }}"
                                                                    class="btn btn-info btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="11">{{ __('No data available!') }}</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
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
    
    <!-- Bulk Action Form -->
    <form id="bulkActionForm" action="{{ route('dashboard.products.bulk-action') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkActionType">
    </form>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Check if SweetAlert2 is loaded
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 is not loaded!');
                alert('Error: SweetAlert2 library is missing. Please refresh the page.');
                return;
            }
            
            // Initialize DataTable
            var table = $('#example2').DataTable({
                responsive: true,
                order: [[1, 'asc']], // Sort by ID column (skip checkbox column)
                pageLength: 25,
                paging: true,
                paginationType: 'full_numbers',
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                columnDefs: [
                    { orderable: false, targets: [0, 2, 10] }, // Checkbox, Image, and Actions columns not sortable
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
                        
                        // Skip Checkbox, Image and Actions columns
                        if (index === 0 || index === 2 || index === 10) {
                            $(column.footer()).html('');
                            return;
                        }
                        
                        // For Category and Sub Category columns, use select dropdown
                        if (index === 3 || index === 4) {
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
                        if (index === 9) {
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
            
            // Multi-select functionality
            var selectedProducts = [];
            
            // Select All checkbox
            $('#selectAll').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.product-checkbox').prop('checked', isChecked);
                updateSelectedProducts();
            });
            
            // Individual checkbox change
            $(document).on('change', '.product-checkbox', function() {
                updateSelectedProducts();
                updateSelectAllState();
            });
            
            function updateSelectedProducts() {
                selectedProducts = [];
                $('.product-checkbox:checked').each(function() {
                    selectedProducts.push($(this).val());
                });
                
                var count = selectedProducts.length;
                $('#selectedCount').text(count + ' {{ __('selected') }}');
                
                if (count > 0) {
                    $('#bulkActionsToolbar').addClass('active');
                } else {
                    $('#bulkActionsToolbar').removeClass('active');
                }
            }
            
            function updateSelectAllState() {
                var total = $('.product-checkbox').length;
                var checked = $('.product-checkbox:checked').length;
                $('#selectAll').prop('checked', total === checked && total > 0);
            }
            
            // Bulk Actions
            function performBulkAction(action) {
                if (selectedProducts.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ __('No Selection') }}',
                        text: '{{ __('Please select at least one product') }}'
                    });
                    return;
                }
                
                var actionText = '';
                var confirmText = '';
                switch(action) {
                    case 'activate':
                        actionText = '{{ __('activate') }}';
                        confirmText = '{{ __('Are you sure you want to activate') }} ' + selectedProducts.length + ' {{ __('product(s)?') }}';
                        break;
                    case 'deactivate':
                        actionText = '{{ __('deactivate') }}';
                        confirmText = '{{ __('Are you sure you want to deactivate') }} ' + selectedProducts.length + ' {{ __('product(s)?') }}';
                        break;
                    case 'delete':
                        actionText = '{{ __('delete') }}';
                        confirmText = '{{ __('Are you sure you want to delete') }} ' + selectedProducts.length + ' {{ __('product(s)? This action cannot be undone!') }}';
                        break;
                    case 'export':
                        // Export doesn't need confirmation
                        $('#bulkActionType').val('export');
                        selectedProducts.forEach(function(id) {
                            $('#bulkActionForm').append('<input type="hidden" name="product_ids[]" value="' + id + '">');
                        });
                        $('#bulkActionForm').submit();
                        return;
                }
                
                Swal.fire({
                    title: '{{ __('Are you sure?') }}',
                    text: confirmText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC143C',
                    cancelButtonColor: '#696969',
                    cancelButtonText: "{{ __('Cancel') }}",
                    confirmButtonText: '{{ __('Yes, do it!') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Clear any previous product IDs
                        $('#bulkActionForm input[name="product_ids[]"]').remove();
                        
                        // Add selected product IDs
                        selectedProducts.forEach(function(id) {
                            $('#bulkActionForm').append('<input type="hidden" name="product_ids[]" value="' + id + '">');
                        });
                        
                        $('#bulkActionType').val(action);
                        $('#bulkActionForm').submit();
                    }
                });
            }
            
            $('#bulkActivate').on('click', function() { performBulkAction('activate'); });
            $('#bulkDeactivate').on('click', function() { performBulkAction('deactivate'); });
            $('#bulkDelete').on('click', function() { performBulkAction('delete'); });
            $('#bulkExport').on('click', function() { performBulkAction('export'); });
            
            // Amazon-style Filters
            function applyFilters() {
                var visibleRows = table.rows({ search: 'applied' }).nodes();
                
                // Status filter
                var statusFilters = [];
                $('.filter-check[data-filter="status"]:checked').each(function() {
                    statusFilters.push($(this).val());
                });
                
                // Stock filter
                var stockFilters = [];
                $('.filter-check[data-filter="stock"]:checked').each(function() {
                    stockFilters.push($(this).val());
                });
                
                // Category filter
                var categoryFilters = [];
                $('.filter-check[data-filter="category"]:checked').each(function() {
                    categoryFilters.push($(this).val());
                });
                
                // Price filter
                var priceMin = parseFloat($('#price-min').val()) || 0;
                var priceMax = parseFloat($('#price-max').val()) || Infinity;
                
                // Custom filter function
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        var row = table.row(dataIndex).node();
                        var $row = $(row);
                        
                        // Status filter
                        if (statusFilters.length > 0) {
                            var rowStatus = $row.data('status');
                            if (!statusFilters.includes(rowStatus)) return false;
                        }
                        
                        // Stock filter
                        if (stockFilters.length > 0) {
                            var stock = parseInt($row.data('stock')) || 0;
                            var stockMatch = false;
                            stockFilters.forEach(function(filter) {
                                if (filter === 'in' && stock > 10) stockMatch = true;
                                if (filter === 'low' && stock > 0 && stock <= 10) stockMatch = true;
                                if (filter === 'out' && stock === 0) stockMatch = true;
                            });
                            if (!stockMatch) return false;
                        }
                        
                        // Category filter
                        if (categoryFilters.length > 0) {
                            var categoryId = $row.data('category');
                            if (!categoryFilters.includes(String(categoryId))) return false;
                        }
                        
                        // Price filter
                        var price = parseFloat($row.data('price')) || 0;
                        if (price < priceMin || price > priceMax) return false;
                        
                        return true;
                    }
                );
                
                table.draw();
            }
            
            // Remove old custom filters before applying new ones
            function clearCustomFilters() {
                $.fn.dataTable.ext.search.pop();
            }
            
            $('.filter-check').on('change', function() {
                clearCustomFilters();
                applyFilters();
            });
            
            $('#applyPriceFilter').on('click', function() {
                clearCustomFilters();
                applyFilters();
            });
            
            $('#clearFilters').on('click', function() {
                $('.filter-check').prop('checked', false);
                $('#price-min, #price-max').val('');
                clearCustomFilters();
                table.search('').draw();
            });
            
            // Delete button functionality
            $(document).on('click', '.delete-country-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                let id = $(this).data('id');
                
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
