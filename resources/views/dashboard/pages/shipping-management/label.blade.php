<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label - Shipment #{{ $shipment->id }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .label-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }
        .label-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .label-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .label-col {
            flex: 1;
            padding: 0 10px;
        }
        .label-section {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
        }
        .label-section h6 {
            margin: 0 0 10px 0;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .barcode {
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 24px;
            letter-spacing: 3px;
            margin: 10px 0;
        }
        .tracking-number {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">Print Label</button>
        <a href="{{ route('dashboard.shipping-management.show', $shipment->id) }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="label-container">
        <div class="label-header">
            <h2>SHIPPING LABEL</h2>
            <p>Shipment #{{ $shipment->id }} | Order #{{ $shipment->order->order_number ?? 'N/A' }}</p>
        </div>

        @if($shipment->tracking_number)
        <div class="tracking-number">
            Tracking: {{ $shipment->tracking_number }}
        </div>
        <div class="barcode">
            {{ $shipment->tracking_number }}
        </div>
        @endif

        <div class="label-row">
            <div class="label-col">
                <div class="label-section">
                    <h6>FROM (PICKUP)</h6>
                    <p><strong>{{ $shipment->vendor_name ?? 'N/A' }}</strong></p>
                    <p>{{ $shipment->vendor_address ?? 'N/A' }}</p>
                    <p>{{ $shipment->vendor_city ?? 'N/A' }}, {{ $shipment->vendor_governorate ?? 'N/A' }}</p>
                    <p>Phone: {{ $shipment->vendor_phone ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="label-col">
                <div class="label-section">
                    <h6>TO (DELIVERY)</h6>
                    <p><strong>{{ $shipment->customer_name ?? 'N/A' }}</strong></p>
                    <p>{{ $shipment->customer_address ?? 'N/A' }}</p>
                    <p>{{ $shipment->customer_city ?? 'N/A' }}, {{ $shipment->customer_governorate ?? 'N/A' }}</p>
                    <p>Phone: {{ $shipment->customer_phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="label-section">
            <h6>PACKAGE DETAILS</h6>
            <div class="label-row">
                <div class="label-col">
                    <p><strong>Weight:</strong> {{ $shipment->package_weight ?? 'N/A' }} grams</p>
                    <p><strong>Dimensions:</strong> 
                        @if($shipment->package_length)
                            {{ $shipment->package_length }} x {{ $shipment->package_width }} x {{ $shipment->package_height }} cm
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="label-col">
                    <p><strong>Provider:</strong> {{ ucfirst($shipment->shipping_provider ?? 'Manual') }}</p>
                    <p><strong>Method:</strong> {{ ucfirst($shipment->shipping_method ?? 'Standard') }}</p>
                    @if($shipment->shipping_cost)
                    <p><strong>Cost:</strong> {{ number_format($shipment->shipping_cost, 2) }} EGP</p>
                    @endif
                </div>
            </div>
            @if($shipment->package_description)
            <p><strong>Contents:</strong> {{ $shipment->package_description }}</p>
            @endif
        </div>

        <div class="label-section">
            <h6>ITEMS</h6>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #ccc;">
                        <th style="text-align: left; padding: 5px;">Product</th>
                        <th style="text-align: center; padding: 5px;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shipment->items as $item)
                    <tr>
                        <td style="padding: 5px;">{{ $item->product->product_name_en ?? 'Product Deleted' }}</td>
                        <td style="text-align: center; padding: 5px;">{{ $item->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($shipment->notes)
        <div class="label-section">
            <h6>NOTES</h6>
            <p>{{ $shipment->notes }}</p>
        </div>
        @endif

        <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
            Generated on {{ now()->format('M d, Y H:i') }} | Phyzioline Shipping System
        </div>
    </div>
</body>
</html>

