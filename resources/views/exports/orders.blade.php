<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th width="20" height="70" style="vertical-align: middle; font-size: 10px;">#</th>
                <th width="20" height="70" style="vertical-align: middle; font-size: 10px;">Order Code</th>
                <th width="20" height="70" style="vertical-align: middle; font-size: 10px;">Order Customer</th>
                <th width="20" height="70" style="vertical-align: middle; font-size: 10px;">Total Price</th>
                <th width="20" height="70" style="vertical-align: middle; font-size: 10px;">Order Status</th>
                <th width="20" height="70" style="vertical-align: middle; font-size: 10px;">Payment Status</th>
                <th width="20" height="70" style="vertical-align: middle; font-size: 10px;">Created On</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $key => $order)
                <tr>
                    <td style="text-align: left">
                        {{ $key + 1}}
                    </td>
                    <td>
                        {{ $order->order_no }}
                    </td>
                    <td>
                        {{ $order->orderCustomer->first_name . $order->orderCustomer->last_name }}
                    </td>
                    <td style="text-align: left">
                        {{ number_format($order->total_price) }}
                    </td>
                    <td>
                        {{ $order->order_status }}
                    </td>
                    <td>
                        {{ $order->payment_status }}
                    </td>
                    <td>
                        {{ Carbon\Carbon::parse($order->created_at)->format('d-M-Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
