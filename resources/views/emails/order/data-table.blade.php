@php
    $totalPaid = $order->transactions->sum('amount');
    $totalDue = $order->total_price - $totalPaid;
@endphp
<table class="table-border mb-5">
    <thead>
        <tr>
            <th width="200px" class="text-start">Product</th>
            <th class="text-end">Quantity</th>
            <th class="text-end">Unit price </th>
            <th class="text-end">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->orderItems as $product)
            <tr>
                <td>
                    {{ $product->product_name }}
                    <small class="text-sm-gray">
                        {{ $product->sku_name }}
                    </small>
                </td>
                <td class="text-end">{{ $product->qty }}</td>
                <td class="text-end">{{ customer_currency($product->original_price, false) }} </td>
                <td class="text-end">
                    {{ customer_currency($product->original_price * $product->qty, false) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<table class="table-border mb-5">
    <tbody>
        <tr>
            <td width="450px" colspan="3" class="text-end">Subtotal:</td>
            <td class="total text-end">{{ customer_currency($order->subtotal, false) }} </td>
        </tr>
        <tr>
            <td width="450px" colspan="3" class="text-end">Payment Method:</td>
            <td class="total text-end">{{ $order->payment->title }}</td>
        </tr>
        <tr>
            <td width="450px" colspan="3" class="text-end"> Tax:</td>
            <td class="total text-end">{{ customer_currency($order->total_tax, false) }} </td>
        </tr>
        @if ($order->total_discount)
            <tr>
                <td width="450px" colspan="3" class="text-end">Total Discount:</td>
                <td class="total text-end">{{ customer_currency($order->total_discount, false) }} </td>
            </tr>
        @endif
        @if ($order->membership_discount)
            <tr>
                <td width="450px" colspan="3" class="text-end">Membership Discount
                    ({{ $order->membership_discount }}%):</td>
                <td class="total text-end">
                    {{ customer_currency($order->membership_discount_amount, false) }} </td>
            </tr>
        @endif
        <tr>
            <td width="200px" colspan="3" class="text-end">Grand Total
                ({{ $setting['currency'] }}):
            </td>
            <td class="total text-end">{{ customer_currency($order->total_price, false) }} </td>
        </tr>
        <tr>
            <td width="200px" colspan="3" class="text-end">Total Paid ({{ $setting['currency'] }}):
            </td>
            <td class="total text-end">{{ customer_currency($totalPaid, false) }} </td>
        </tr>
        <tr>
            <td width="200px" colspan="3" class="text-end">Total Due ({{ $setting['currency'] }}):
            </td>
            <td class="total text-end">{{ customer_currency($totalDue, false) }} </td>
        </tr>

    </tbody>
</table>
<hr>
<h3 class="mb-3">Billing Address</h3>
<ul class="list-style-none mb-3" style="line-height: 1.5rem">
    <li>{{ $order->orderCustomer->first_name }} {{ $order->orderCustomer->last_name }}</li>
    <li>{{ $order->orderCustomer->address }}</li>
    <li>{{ $order->orderCustomer->phone }}</li>
    <li>{{ $order->orderCustomer->email }}</li>
</ul>
<hr>
