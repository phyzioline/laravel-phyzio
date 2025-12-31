@extends('dashboard.layouts.app')
@section('title', 'Shipping Label')
@section('content')
<style>
    /* Screen styles */
    .shipping-label {
        max-width: 800px;
        margin: 20px auto;
        padding: 30px;
        border: 2px solid #000;
        background: white;
    }
    
    /* Print styles - Only show the label, fit on one page */
    @media print {
        /* Page settings - A4 size */
        @page {
            size: A4;
            margin: 0.5cm;
        }
        
        /* Hide EVERYTHING except printable area */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: auto !important;
            background: white !important;
            overflow: visible !important;
        }
        
        /* Hide all dashboard layout elements */
        body > *:not(#printable-area),
        .sidebar,
        .main-header,
        .navbar,
        .breadcrumb,
        header,
        nav,
        aside,
        footer,
        .no-print,
        .btn,
        button,
        .main-wrapper > *:not(#printable-area),
        .main-content > *:not(#printable-area) {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            position: absolute !important;
            left: -9999px !important;
        }
        
        /* Show only the printable area */
        .main-wrapper,
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            background: white !important;
        }
        
        /* Label container - fit on one page */
        #printable-area {
            position: relative !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 15px !important;
            border: 2px solid #000 !important;
            background: white !important;
            box-shadow: none !important;
            page-break-inside: avoid;
            page-break-after: avoid;
            font-size: 11px !important;
        }
        
        /* Compact header */
        .label-header {
            padding: 10px 0 !important;
            margin-bottom: 10px !important;
        }
        
        .label-header h2 {
            font-size: 20px !important;
            margin: 5px 0 !important;
        }
        
        .label-header h4 {
            font-size: 14px !important;
            margin: 5px 0 !important;
        }
        
        .barcode {
            font-size: 40px !important;
            margin: 10px 0 !important;
        }
        
        /* Compact sections */
        .label-section {
            margin-bottom: 10px !important;
            padding: 10px !important;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .label-section h5 {
            font-size: 12px !important;
            margin-bottom: 8px !important;
            padding-bottom: 5px !important;
        }
        
        .label-section p {
            margin: 3px 0 !important;
            font-size: 10px !important;
        }
        
        /* Compact table */
        .label-section table {
            font-size: 9px !important;
            margin: 5px 0 !important;
        }
        
        .label-section table th,
        .label-section table td {
            padding: 4px !important;
        }
        
        /* Compact info rows */
        .info-row {
            margin-bottom: 5px !important;
            font-size: 10px !important;
        }
        
        /* Compact footer */
        .text-center.mt-4 {
            margin-top: 10px !important;
            padding-top: 8px !important;
            font-size: 9px !important;
        }
        
        /* Ensure all content displays */
        #printable-area * {
            visibility: visible !important;
        }
        
        /* Fix inline elements */
        #printable-area span,
        #printable-area p,
        #printable-area strong,
        #printable-area em {
            display: inline !important;
        }
        
        /* Fix table display */
        #printable-area table {
            display: table !important;
            width: 100% !important;
            page-break-inside: avoid;
        }
        
        #printable-area tr {
            display: table-row !important;
        }
        
        #printable-area td,
        #printable-area th {
            display: table-cell !important;
        }
        
        /* Text colors for print */
        #printable-area {
            color: #000 !important;
        }
        
        /* Keep brand color for headers */
        #printable-area .label-section h5,
        #printable-area h2[style*="#02767F"] {
            color: #02767F !important;
        }
        
        /* Background colors */
        #printable-area .label-section {
            background: white !important;
        }
        
        #printable-area .label-section[style*="background-color: #f8f9fa"] {
            background: #f8f9fa !important;
        }
        
        #printable-area .label-section[style*="background-color: #fff3cd"] {
            background: #fff3cd !important;
        }
        
        /* Fix badge display */
        #printable-area .badge {
            display: inline-block !important;
            padding: 2px 6px !important;
            font-size: 9px !important;
            border: 1px solid #000 !important;
        }
        
        /* Remove icons in print */
        #printable-area .fa,
        #printable-area i {
            display: none !important;
        }
    }
    
    .label-header {
        text-align: center;
        border-bottom: 3px solid #02767F;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }
    
    .label-section {
        margin-bottom: 25px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    
    .label-section h5 {
        color: #02767F;
        border-bottom: 2px solid #02767F;
        padding-bottom: 8px;
        margin-bottom: 15px;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .barcode {
        text-align: center;
        font-family: 'Libre Barcode 128', cursive;
        font-size: 60px;
        letter-spacing: 2px;
        margin: 20px 0;
    }
</style>

<div class="text-center mb-4 no-print" style="padding: 20px;">
    <button onclick="window.print()" class="btn btn-primary btn-lg">
        <i class="fa fa-print"></i> Print Label
    </button>
    <button onclick="window.close()" class="btn btn-secondary btn-lg">
        <i class="fa fa-times"></i> Close
    </button>
</div>

<main class="main-wrapper">
    <div class="main-content">
        <div id="printable-area" class="shipping-label">
            {{-- Header --}}
            <div class="label-header">
                <h2 style="color: #02767F; margin-bottom: 10px;">PHYZIOLINE</h2>
                <h4>Shipping Label / بوليصة شحن</h4>
                <div class="barcode">{{ $order->id }}</div>
                <p style="margin: 0;">Order #{{ $order->id }} | {{ $order->created_at->format('d/m/Y') }}</p>
            </div>

            {{-- Shipping From --}}
            <div class="label-section">
                <h5><i class="fa fa-store"></i> From / من</h5>
                <p><strong>PHYZIOLINE</strong></p>
                <p>Address: Cairo, Egypt</p>
                <p>Phone: +20 123 456 7890</p>
                <p>Email: info@phyzioline.com</p>
            </div>

            {{-- Shipping To --}}
            <div class="label-section" style="background-color: #f8f9fa;">
                <h5><i class="fa fa-map-marker-alt"></i> Ship To / إلى</h5>
                <p><strong>{{ $order->name }}</strong></p>
                <p><i class="fa fa-map-marker"></i> {{ $order->address }}</p>
                <p><i class="fa fa-phone"></i> {{ $order->phone }}</p>
                @if($order->email || $order->user->email)
                    <p><i class="fa fa-envelope"></i> {{ $order->email ?? $order->user->email }}</p>
                @endif
            </div>

            {{-- Order Details --}}
            <div class="label-section">
                <h5><i class="fa fa-box"></i> Order Information / تفاصيل الطلب</h5>
                <div class="info-row">
                    <span><strong>Order ID:</strong></span>
                    <span>#{{ $order->id }}</span>
                </div>
                <div class="info-row">
                    <span><strong>Order Date:</strong></span>
                    <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span><strong>Payment Method:</strong></span>
                    <span>{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div class="info-row">
                    <span><strong>Payment Status:</strong></span>
                    <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                <div class="info-row">
                    <span><strong>Total Amount:</strong></span>
                    <span style="font-size:20px; color: #02767F;"><strong>{{ $order->total }} EGP</strong></span>
                </div>
            </div>

            {{-- Products List --}}
            <div class="label-section">
                <h5><i class="fa fa-list"></i> Products / المنتجات ({{ $order->items->count() }} items)</h5>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr style="background-color: #02767F; color: white;">
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product?->{'product_name_' . app()->getLocale()} ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price }} EGP</td>
                            <td>{{ $item->total }} EGP</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f8f9fa; font-weight: bold;">
                            <td colspan="4" class="text-end">Grand Total:</td>
                            <td>{{ $order->total }} EGP</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Shipping Instructions --}}
            <div class="label-section" style="background-color: #fff3cd;">
                <h5><i class="fa fa-exclamation-triangle"></i> Shipping Instructions / تعليمات الشحن</h5>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Handle with care</li>
                    <li>Keep in dry place</li>
                    <li>Do not bend</li>
                    <li>Contact recipient before delivery</li>
                </ul>
            </div>

            {{-- Footer --}}
            <div class="text-center mt-4" style="border-top: 2px solid #ddd; padding-top: 15px;">
                <p style="margin: 0; color: #666;">
                    This is an official shipping document | هذه وثيقة شحن رسمية
                </p>
                <p style="margin: 5px 0; font-size: 12px; color: #999;">
                    Printed on: {{ now()->format('d/m/Y H:i:s') }}
                </p>
            </div>
        </div>
    </div>
</main>
@endsection
