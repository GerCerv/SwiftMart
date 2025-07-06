<!DOCTYPE html>
<html>
<head>
    <title>Order Status Updated</title>
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
    <h1>Your Order Status Has Been Updated</h1>
    
    <h2>Order #{{$order->id}} Details:</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                <td>{{ $item->status}}</td>
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
    
    @foreach($items as $item)
    <p>Current Status: <strong>{{ $item->status }}</strong></p>
    @if($item->status === 'Processing')
        <p>Your order {{ $item->product->name }} is now being prepared by the vendor.</p>
    @elseif($item->status === 'Ready for Pickup')
        <p>Your order {{ $item->product->name }} is ready for pickup!</p>
    @elseif($item->status === 'Out for Delivery')
        <p>Your order {{ $item->product->name }} is on its way to you!</p>
    @endif
    @endforeach

    <p>Thank you for shopping with us!</p>
</body>
</html>