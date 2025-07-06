<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Thank you for your order!</h1>
    <p>Your order #{{ $order->id }} has been successfully placed.</p>

    <p>Please wait for the vendor to approve your order. You will receive another email once your order is approved and shipped.</p>
    <p>Shipping Address: {{ $order->shipping_address }}</p>
    <p>Thank you for shopping with us!</p>
    
    <h2>Order Details:</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>



    <h2>Order Summary:</h2>
    <table>
        <thead>
            <tr>
                <th>Subtotal</th>
                <th>Discount</th>
                <th>Shipping</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            
            <tr>
                <td>{{ number_format($order->subtotal, 2) }}</td>
                <td>{{ number_format($order->discount, 2) }}</td>
                <td>{{ number_format($order->shipping, 2) }}</td>
                <td>{{ number_format($order->total, 2) }}</td>
            </tr>
            
        </tbody>
    </table>

    
    
</body>
</html>