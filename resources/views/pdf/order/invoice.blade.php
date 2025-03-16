@extends('pdf.app')
@section('content')
    @php
        $totalPaid = $order->transactions->sum('amount');
        $totalDue = $order->total_price - $totalPaid;
    @endphp
    <table class="vertical-middle mt-5">
        <tbody>
            <tr>
                <td>
                    <span class="font-w-medium mb-3 block">Bill to:</span>
                    <span class="mb-3 block">
                        {{ convertToZawgyi($order->orderCustomer->first_name) }}
                        {{ convertToZawgyi($order->orderCustomer->last_name) }}
                    </span>
                    <span class="mb-3 block">
                        {{ convertToZawgyi($order->orderCustomer->address) }},
                        {{ convertToZawgyi($order->orderCustomer->country) }}
                    </span>
                    <span class="mb-3 block">
                        {{ $order->orderCustomer->phone ?? '---' }}
                    </span>
                    <span class="mb-3 block">
                        {{ $order->orderCustomer->email ?? '---' }}
                    </span>
                </td>
                <td class="text-end">
                    <span class="mb-3 block">
                        {{ $setting['app_name'] }}
                    </span>
                    <span class="mb-3 block" style="width: 50%; margin-left: auto">
                        {!! nl2br(e(convertToZawgyi($setting['address']))) !!}
                    </span>
                    <span class="mb-3 block">
                        {{ $setting['phone'] }}
                    </span>
                    <span class="mb-3 block">
                        {{ $setting['email'] }}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="mb-5">
        <h3 class="mb-3 block">
            <span class="font-w-light">
                INVOICE:
            </span> #{{ $order->order_no }}
        </h3>
        <span class="mb-3 block">Invoice Date: {{ $order->created_at->format('d M Y') }}</span>
    </div>

    <table class="table-border">
        <thead>
            <tr>
                <th class="text-start">Product</th>
                <th class="text-end">Quantity</th>
                <th class="text-end">Unit Price</th>
                <th class="text-end"> Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $product)
                <tr>
                    <td>
                        {{ convertToZawgyi($product->product_name) }}
                        <small class="text-sm-gray">
                            {{ convertToZawgyi($product->sku_name) }}
                        </small>
                    </td>
                    <td class="text-end">{{ $product->qty }}</td>
                    <td class="text-end"> {{ customer_currency($product->original_price, false) }} </td>
                    <td class="text-end">{{ customer_currency($product->original_price * $product->qty, false) }} </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-end">Subtotal:</td>
                <td class="total">{{ customer_currency($order->subtotal, false) }} </td>
            </tr>
            <tr>
                <td colspan="3" class="text-end"> Tax:</td>
                <td class="total">{{ customer_currency($order->total_tax, false) }} </td>
            </tr>
            @if ($order->total_discount)
                <tr>
                    <td colspan="3" class="text-end">Total Discount:</td>
                    <td class="total">{{ customer_currency($order->total_discount, false) }} </td>
                </tr>
            @endif
            @if ($order->membership_discount)
                <tr>
                    <td colspan="3" class="text-end">Membership Discount ({{ $order->membership_discount }}%):</td>
                    <td class="total">{{ customer_currency($order->membership_discount_amount, false) }} </td>
                </tr>
            @endif
            <tr>
                <td colspan="3" class="text-end">Grand Total ({{ $setting['currency'] }}):</td>
                <td class="total">{{ customer_currency($order->total_price, false) }} </td>
            </tr>
            <tr>
                <td colspan="3" class="text-end">Total Paid ({{ $setting['currency'] }}):</td>
                <td class="total">{{ customer_currency($totalPaid, false) }} </td>
            </tr>
            <tr>
                <td colspan="3" class="text-end">Total Due ({{ $setting['currency'] }}):</td>
                <td class="total">{{ customer_currency($totalDue, false) }} </td>
            </tr>
        </tbody>
    </table>

    <div class="mt-5">
        <h4 style="margin-bottom: -10px">Terms & Condition</h4>
        <p>
            By using our site, you agree to our terms, including order acceptance, pricing, payment, shipping, returns, and
            liability limits.
        </p>
    </div>
@endsection
