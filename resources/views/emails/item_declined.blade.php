<!DOCTYPE html>
<html>
<head>
    <title>Item Declined</title>
</head>
<body>
    <h2>Item Declined Notification</h2>
    <p>Hello,</p>
    <p>Your product <strong>{{ $productName }}</strong> has been declined by delivery personnel.</p>
    <p><strong>Delivery Person:</strong> {{ $deliveryManName }}</p>
    <p><strong>Reason:</strong> {{ $reason }}</p>
    <p>Please log in to your vendor dashboard for more details.</p>
    <p>Thank you</p>
</body>
</html>