<!DOCTYPE html>
<html>
<head>
    <title>New Order Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #04b8c4;">New Order Notification</h2>
        <p>You have received a new order!</p>
        
        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3>Order Details</h3>
            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
            <p><strong>Customer Name:</strong> {{ $order->name }}</p>
            <p><strong>Phone:</strong> {{ $order->phone }}</p>
            <p><strong>Address:</strong> {{ $order->address }}</p>
        </div>

        <h3>Items to Ship:</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #eee;">
                    <th style="padding: 10px; text-align: left;">Product</th>
                    <th style="padding: 10px; text-align: center;">Qty</th>
                    <th style="padding: 10px; text-align: right;">SKU</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">
                        {{ $item->product->product_name_en ?? 'Product' }}
                    </td>
                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #eee;">
                        {{ $item->quantity }}
                    </td>
                    <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;">
                        {{ $item->product->sku ?? '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top: 30px;">Please login to your dashboard to process this order.</p>
    </div>
</body>
</html>
