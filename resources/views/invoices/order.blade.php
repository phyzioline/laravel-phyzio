<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Invoice') }} #{{ $invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .invoice-header {
            border-bottom: 3px solid #02767F;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .invoice-header h1 {
            color: #02767F;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .company-info, .invoice-info {
            flex: 1;
        }
        .company-info h3 {
            color: #02767F;
            margin-bottom: 10px;
        }
        .invoice-info {
            text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }};
        }
        .invoice-info strong {
            color: #666;
        }
        .customer-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin: 30px 0;
        }
        .customer-section h3 {
            color: #02767F;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        table thead {
            background: #02767F;
            color: white;
        }
        table th, table td {
            padding: 12px;
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
            border-bottom: 1px solid #ddd;
        }
        table th {
            font-weight: 600;
        }
        table tbody tr:hover {
            background: #f9f9f9;
        }
        .text-right {
            text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }};
        }
        .totals {
            margin-top: 20px;
        }
        .totals table {
            margin: 0;
            margin-left: auto;
            width: 300px;
        }
        .totals table td {
            padding: 8px 12px;
        }
        .totals table tr:last-child {
            background: #02767F;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <h1>{{ __('INVOICE') }}</h1>
            <div class="invoice-meta">
                <div class="company-info">
                    <h3>{{ $company['name'] }}</h3>
                    @if($company['address'])
                        <p>{{ $company['address'] }}</p>
                    @endif
                    @if($company['phone'])
                        <p>{{ __('Phone') }}: {{ $company['phone'] }}</p>
                    @endif
                    @if($company['email'])
                        <p>{{ __('Email') }}: {{ $company['email'] }}</p>
                    @endif
                </div>
                <div class="invoice-info">
                    <p><strong>{{ __('Invoice Number') }}:</strong> {{ $invoice_number }}</p>
                    <p><strong>{{ __('Invoice Date') }}:</strong> {{ $invoice_date }}</p>
                    <p><strong>{{ __('Order Number') }}:</strong> {{ $order->order_number }}</p>
                    <p><strong>{{ __('Order Date') }}:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="customer-section">
            <h3>{{ __('Bill To') }}</h3>
            <p><strong>{{ __('Name') }}:</strong> {{ $customer['name'] }}</p>
            @if($customer['email'])
                <p><strong>{{ __('Email') }}:</strong> {{ $customer['email'] }}</p>
            @endif
            @if($customer['phone'])
                <p><strong>{{ __('Phone') }}:</strong> {{ $customer['phone'] }}</p>
            @endif
            @if($customer['address'])
                <p><strong>{{ __('Address') }}:</strong> {{ $customer['address'] }}</p>
            @endif
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>{{ __('#') }}</th>
                    <th>{{ __('Product') }}</th>
                    <th class="text-right">{{ __('Quantity') }}</th>
                    <th class="text-right">{{ __('Unit Price') }}</th>
                    <th class="text-right">{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->product->{'product_name_' . app()->getLocale()} ?? __('Product') }}</strong>
                            @if($item->product)
                                <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                            @endif
                            @if($item->engineer_selected)
                                <br><small class="text-info">
                                    <i class="fa fa-user-md"></i> {{ __('Medical Engineer Installation') }} 
                                    (+{{ number_format($item->engineer_price, 2) }} {{ __('EGP') }})
                                </small>
                            @endif
                        </td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->price, 2) }} {{ __('EGP') }}</td>
                        <td class="text-right">{{ number_format($item->total, 2) }} {{ __('EGP') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table>
                <tr>
                    <td><strong>{{ __('Subtotal') }}:</strong></td>
                    <td class="text-right">{{ number_format($subtotal, 2) }} {{ __('EGP') }}</td>
                </tr>
                @if($shipping > 0)
                <tr>
                    <td><strong>{{ __('Shipping') }}:</strong></td>
                    <td class="text-right">{{ number_format($shipping, 2) }} {{ __('EGP') }}</td>
                </tr>
                @endif
                @if(isset($tax) && $tax > 0)
                <tr>
                    <td><strong>{{ __('Tax') }}:</strong></td>
                    <td class="text-right">{{ number_format($tax, 2) }} {{ __('EGP') }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>{{ __('Total') }}:</strong></td>
                    <td class="text-right">{{ number_format($total, 2) }} {{ __('EGP') }}</td>
                </tr>
            </table>
        </div>

        <!-- Payment Information -->
        <div style="margin-top: 30px; padding: 15px; background: #f9f9f9; border-radius: 5px;">
            <p><strong>{{ __('Payment Method') }}:</strong> 
                {{ $order->payment_method === 'cash' ? __('Cash on Delivery') : __('Credit Card') }}
            </p>
            <p><strong>{{ __('Payment Status') }}:</strong> 
                <span style="color: {{ $order->payment_status === 'paid' ? 'green' : 'orange' }};">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('Thank you for your business!') }}</p>
            <p>{{ __('This is an official invoice from') }} {{ $company['name'] }}</p>
            @if($company['tax_id'])
                <p>{{ __('Tax ID') }}: {{ $company['tax_id'] }}</p>
            @endif
        </div>

        <!-- Print Button (hidden when printing) -->
        <div class="no-print" style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()" class="btn btn-primary" style="padding: 10px 20px; background: #02767F; color: white; border: none; border-radius: 5px; cursor: pointer;">
                <i class="las la-print"></i> {{ __('Print Invoice') }}
            </button>
        </div>
    </div>
</body>
</html>

