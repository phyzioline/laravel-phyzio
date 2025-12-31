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
        
        /* Filter dropdowns styling */
        .form-select-sm {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
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
        
        /* Table container and scaling - Optimized for laptop screens */
        .table-responsive {
            width: 100% !important;
            max-width: 100% !important;
            max-height: 75vh;
            overflow-x: auto;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        #example2 {
            width: 100% !important;
            min-width: 1600px;
            font-size: 0.85rem;
            table-layout: fixed;
        }
        
        #example2 th,
        #example2 td {
            padding: 6px 8px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        #example2 thead {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa;
        }
        
        /* Ensure card uses full width */
        .card {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box;
        }
        
        /* Responsive table adjustments */
        @media screen and (max-width: 1200px) {
            #example2 {
                font-size: 0.75rem;
            }
            #example2 th,
            #example2 td {
                padding: 4px 6px !important;
            }
        }
        
        /* Ensure proper width calculation accounting for sidebar */
        @media screen and (min-width: 992px) {
            .main-wrapper {
                width: calc(100vw - 260px - 40px) !important;
            }
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
                        <div class="add d-flex justify-content-between align-items-center p-2 gap-2 flex-wrap">
                            <!-- Filter Dropdowns -->
                            <div class="d-flex gap-2 flex-wrap">
                                <!-- Status Filter -->
                                <div class="btn-group">
                                    <select class="form-select form-select-sm" id="filterStatus" style="min-width: 150px;">
                                        <option value="">{{ __('All Status') }}</option>
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="inactive">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                                
                                <!-- Stock Filter -->
                                <div class="btn-group">
                                    <select class="form-select form-select-sm" id="filterStock" style="min-width: 150px;">
                                        <option value="">{{ __('All Stock') }}</option>
                                        <option value="in">{{ __('In Stock') }} (>10)</option>
                                        <option value="low">{{ __('Low Stock') }} (1-10)</option>
                                        <option value="out">{{ __('Out of Stock') }}</option>
                                    </select>
                                </div>
                                
                                <!-- Category Filter -->
                                <div class="btn-group">
                                    <select class="form-select form-select-sm" id="filterCategory" style="min-width: 180px;">
                                        <option value="">{{ __('All Categories') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->{'name_' . app()->getLocale()} }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Price Range -->
                                <div class="btn-group d-flex gap-1">
                                    <input type="number" id="price-min" placeholder="{{ __('Min') }}" class="form-control form-control-sm" style="width: 80px;">
                                    <input type="number" id="price-max" placeholder="{{ __('Max') }}" class="form-control form-control-sm" style="width: 80px;">
                                </div>
                                
                                <!-- Clear Filters -->
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="clearFilters">
                                    <i class="fas fa-times"></i> {{ __('Clear') }}
                                </button>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
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
                            <div class="table-responsive text-center" style="max-height: 75vh; overflow-x: auto; overflow-y: auto; width: 100%;">
                                        <table id="example2" class="table table-striped table-bordered table-sm" style="font-size: 0.85rem; width: 100%; min-width: 1600px;">
                                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10; background-color: #f8f9fa;">
                                                <tr>
                                                    <th class="checkbox-column" style="min-width: 40px; width: 2%;">
                                                        <input type="checkbox" id="selectAll">
                                                    </th>
                                                    <th style="min-width: 60px; width: 3%;">{{ __('ID') }}</th>
                                                    <th style="min-width: 90px; width: 5%;">{{ __('Image') }}</th>
                                                    <th style="min-width: 120px; width: 8%;">{{ __('Category') }}</th>
                                                    <th style="min-width: 120px; width: 8%;">{{ __('Sub Category') }}</th>
                                                    <th style="min-width: 300px; width: 25%;">{{ __('Product Name') }}</th>
                                                    <th style="min-width: 90px; width: 6%;">{{ __('Price') }}</th>
                                                    <th style="min-width: 80px; width: 5%;">{{ __('Amount') }}</th>
                                                    <th style="min-width: 120px; width: 8%;">{{ __('SKU') }}</th>
                                                    <th style="min-width: 90px; width: 6%;">{{ __('Status') }}</th>
                                                    <th style="min-width: 150px; width: 10%;">{{ __('Actions') }}</th>
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
                                                        <td style="font-size: 0.8rem;">{{ $product->id }}</td>
                                                        <td>
                                                            <div style="width: 60px; height: 60px; overflow: hidden; border-radius: 6px; border: 1px solid #eee; display: flex; align-items: center; justify-content: center; background: #fff;">
                                                                <img src="{{ asset($product->productImages->first()?->image) }}"
                                                                    alt="Product"
                                                                    style="width: 100%; height: 100%; object-fit: contain;">
                                                            </div>
                                                        </td>
                                                        <td style="font-size: 0.75rem;">{{ Str::limit($product->category?->{'name_' . app()->getLocale()} ?? 'N/A', 20) }}</td>
                                                        <td style="font-size: 0.75rem;">{{ Str::limit($product->sub_category?->{'name_' . app()->getLocale()} ?? 'N/A', 20) }}</td>
                                                        <td style="font-size: 0.8rem;">{{ Str::limit($product->{'product_name_' . app()->getLocale()}, 50) }}</td>
                                                        <td style="font-size: 0.8rem;"><strong>{{ number_format($product->product_price, 0) }}</strong><br><small>EGP</small></td>
                                                        <td style="font-size: 0.8rem;">{{ $product->amount }}</td>
                                                        <td style="font-size: 0.75rem;">{{ Str::limit($product->sku, 15) }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}" style="font-size: 0.75rem;">
                                                                {{ $product->status }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group-vertical btn-group-sm" role="group" style="flex-wrap: wrap;">
                                                            @can('products-delete')
                                                                <button type="button" class="btn btn-danger btn-sm delete-country-btn mb-1"
                                                                    data-id="{{ $product->id }}" title="{{ __('Delete') }}" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </button>
                                                            @endcan
                                                            @can('products-show')
                                                                <a href="{{ route('dashboard.products.show', $product) }}"
                                                                    class="btn btn-warning btn-sm mb-1" title="{{ __('View') }}" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endcan
                                                            @can('products-update')
                                                                <a href="{{ route('dashboard.products.edit', $product) }}"
                                                                    class="btn btn-info btn-sm mb-1" title="{{ __('Edit') }}" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @endcan
                                                            </div>
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
                responsive: false,
                autoWidth: false,
                scrollX: true,
                scrollCollapse: true,
                order: [[1, 'asc']], // Sort by ID column (skip checkbox column)
                pageLength: 25,
                paging: true,
                paginationType: 'full_numbers',
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                columnDefs: [
                    { orderable: false, targets: [0, 2, 10] }, // Checkbox, Image, and Actions columns not sortable
                    { width: '2%', targets: 0 }, // Checkbox
                    { width: '3%', targets: 1 }, // ID
                    { width: '5%', targets: 2 }, // Image
                    { width: '8%', targets: 3 }, // Category
                    { width: '8%', targets: 4 }, // Sub Category
                    { width: '25%', targets: 5 }, // Product Name
                    { width: '6%', targets: 6 }, // Price
                    { width: '5%', targets: 7 }, // Amount
                    { width: '8%', targets: 8 }, // SKU
                    { width: '6%', targets: 9 }, // Status
                    { width: '10%', targets: 10 }, // Actions
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
            
            // Dropdown Filters
            function applyFilters() {
                var visibleRows = table.rows({ search: 'applied' }).nodes();
                
                // Status filter
                var statusFilter = $('#filterStatus').val();
                
                // Stock filter
                var stockFilter = $('#filterStock').val();
                
                // Category filter
                var categoryFilter = $('#filterCategory').val();
                
                // Price filter
                var priceMin = parseFloat($('#price-min').val()) || 0;
                var priceMax = parseFloat($('#price-max').val()) || Infinity;
                
                // Custom filter function
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        var row = table.row(dataIndex).node();
                        var $row = $(row);
                        
                        // Status filter
                        if (statusFilter && statusFilter !== '') {
                            var rowStatus = $row.data('status');
                            if (rowStatus !== statusFilter) return false;
                        }
                        
                        // Stock filter
                        if (stockFilter && stockFilter !== '') {
                            var stock = parseInt($row.data('stock')) || 0;
                            var stockMatch = false;
                            if (stockFilter === 'in' && stock > 10) stockMatch = true;
                            if (stockFilter === 'low' && stock > 0 && stock <= 10) stockMatch = true;
                            if (stockFilter === 'out' && stock === 0) stockMatch = true;
                            if (!stockMatch) return false;
                        }
                        
                        // Category filter
                        if (categoryFilter && categoryFilter !== '') {
                            var categoryId = String($row.data('category'));
                            if (categoryId !== categoryFilter) return false;
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
            
            // Filter change events
            $('#filterStatus, #filterStock, #filterCategory').on('change', function() {
                clearCustomFilters();
                applyFilters();
            });
            
            // Price filter - apply on input change
            $('#price-min, #price-max').on('input', function() {
                clearCustomFilters();
                applyFilters();
            });
            
            // Clear all filters
            $('#clearFilters').on('click', function() {
                $('#filterStatus, #filterStock, #filterCategory').val('');
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
