<!DOCTYPE html>
<html>
<head>
    <title>Your Purchase is Complete</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        h1 { color: #f97316; }
        h2, h3 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f7f7f7; font-weight: bold; }
        p { margin: 10px 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .footer { margin-top: 20px; font-size: 0.9em; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Purchase is Complete</h1>
        <p>Dear {{ $orderDetails['user_name'] }},</p>
        <p>Thank you for your order! Your purchase has been successfully placed and is now awaiting approval from the vendor. Below is the summary of your order:</p>

        <h2>Order Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Store</th>
                    <th>Pack Size</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Discount</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderDetails['items'] as $item)
                    <tr>
                        <td>{{ $item['product_name'] }}</td>
                        <td>{{ $item['store_name'] }}</td>
                        <td>{{ $item['pack_size'] }}kg</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>₱{{ number_format($item['unit_price'], 2) }}</td>
                        <td>{{ $item['discount'] }}%</td>
                        <td>₱{{ number_format($item['total'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Summary</h3>
        <p>Subtotal: ₱{{ number_format($orderDetails['subtotal'], 2) }}</p>
        <p>Discount: ₱{{ number_format($orderDetails['discount'], 2) }}</p>
        <p>Shipping: ₱{{ number_format($orderDetails['shipping'], 2) }}</p>
        <p><strong>Total: ₱{{ number_format($orderDetails['total'], 2) }}</strong></p>

        <p>Address: {{ $orderDetails['address'] }}</p>
        <p>Payment Method: {{ $orderDetails['payment_method'] }}</p>
        <p>Order ID: {{ $orderDetails['order_id'] }}</p>

        <p>Please wait for the vendor approval before your order is processed. You will receive further updates once approved.</p>

        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>Best regards,<br>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>