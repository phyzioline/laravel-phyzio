<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #04b8c4;">Order Confirmation</h2>
        <p>Dear {{ $order->name }},</p>
        <p>Thank you for your order! We are pleased to confirm that we have received your order.</p>
        
        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3>Order Details</h3>
            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
            <p><strong>Total Amount:</strong> {{ number_format($order->total, 2) }} EGP</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
        </div>

        <h3>Items Ordered:</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #eee;">
                    <th style="padding: 10px; text-align: left;">Product</th>
                    <th style="padding: 10px; text-align: center;">Qty</th>
                    <th style="padding: 10px; text-align: right;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">
                        {{ $item->product->product_name_en ?? 'Product' }}
                    </td>
                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #eee;">
                        {{ $item->quantity }}
                    </td>
                    <td style="padding: 10px; text-align: right; border-bottom: 1px solid #eee;">
                        {{ number_format($item->price, 2) }} EGP
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top: 30px;">We will notify you when your order is shipped.</p>
        
        <p>Best regards,<br>The PhyzioLine Team</p>
    </div>
</body>
</html>
