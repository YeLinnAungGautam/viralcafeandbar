<?php

namespace App\Services\Api;

use App\Http\Traits\SaveCustomerTrait;
use App\Jobs\OrderMailJob;
use App\Models\Customer;
use App\Models\CustomerMemberShip;
use App\Models\MemberShip;
use App\Models\Order;
use App\Models\OrderCustomer;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Sku;
use App\Notifications\OrderShip;
use App\Notifications\PointTransaction;
use App\Services\FcmNotifyService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use TranslateHelper;

class OrderService
{
    use SaveCustomerTrait;

    public function __construct(public $authId = null)
    {
        $this->authId = $authId ?? Auth::id();
    }

    public function list(Request $request)
    {
        $orders = Order::withCount(['orderItems as total_items'])
            ->latest('id')
            ->paginate($request->limit ?? 1);

        return $orders;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_no'       => Str::upper(Str::random(10)),
                'payment_method' => $request->payment_method,
                'internal_note'  => $request->internal_note,
                'customer_id'    => $this->authId,
            ]);

            $this->_saveOrderItems($request, $order);
            $this->_saveCustomerInfo($request, $order);

            DB::commit();

            $order->refresh();

            $order->load('orderCustomer');

            // if ($request->email) {
            $this->_sendNotification($order);
            // }

            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show(int $id)
    {
        $order = Order::with(['orderItems', 'orderCustomer', 'lastPointTransaction'])
            ->where('customer_id', $this->authId)
            ->where('id', $id)
            ->first();

        return $order;
    }

    public function update(Request $request, Order $order)
    {
        DB::beginTransaction();
        try {
            $oldOrder = clone $order;


            $order->load('transactions', 'orderCustomer');

            $order->update([
                'order_status'   => $request->input('order_status'),
                'payment_status' => $request->input('order_status') == 'confirmed' ? 'paid' : 'unpaid',
            ]);

            if ($order->order_status == 'cancel') {

                $orderItems = OrderItem::where('order_id', $order->id)->get();

                foreach ($orderItems as $key => $value) {
                    // when cancel increase stock
                    Sku::where('product_id', $value->product_id)
                        ->where('id', $value->sku_id)
                        ->increment('stock', $value->qty);
                }
            }

            $this->_savePoint($order, $oldOrder);

            $order->load('orderCustomer');

            DB::commit();

            $this->_sendNotification($order);

            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function _saveOrderItems(Request $request, Order $order)
    {
        $items = $request->items;
        $data  = [];
        foreach ($items as $key => $value) {
            $sku = $this->_getProductSku($value['product_id'], $value['sku_id'], $value['qty']);

            $checkPrice = $this->_checkPrice($sku);

            $unit_price = $checkPrice ? $sku['sale_price'] : $sku['original_price'];

            $total_price = $unit_price * $value['qty'];

            $discount_amount = 0;

            if ($checkPrice) {
                $discount_amount = $sku['original_price'] - $sku['sale_price'];
            }

            $customerMemberShip = CustomerMemberShip::where('customer_id', $order->customer_id)->first();

            $membership_discount = 0;

            if ($customerMemberShip->is_percentage_default) {
                $membership_discount = $customerMemberShip->percentage;
            } else {
                if (!is_null($customerMemberShip->member_ship_id)) {
                    $member              = MemberShip::find($customerMemberShip->member_ship_id);
                    $membership_discount = $member->percentage;
                }
            }

            $data[] = [
                'product_name'    => Product::find($value['product_id'])->name,
                'product_id'      => $value['product_id'],
                'sku_id'          => $value['sku_id'],
                'sku_name'        => $sku['code'],
                'qty'             => $value['qty'],
                'unit_price'      => $unit_price,
                'total_price'     => $total_price,
                'discount_amount' => $discount_amount * $value['qty'],
                'tax_price'       => 0,
                'order_id'        => $order->id,
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        $total_discount = collect($data)->sum('discount_amount');
        $subtotal       = collect($data)->sum('total_price') + $total_discount;
        $tax            = collect($data)->sum('tax_price');

        $total_price                = collect($data)->sum('total_price');
        $membership_discount_amount = $total_price * ($membership_discount / 100);
        $final_price                = $total_price - $membership_discount_amount;

        $order->update([
            'earn'                       => round($final_price / Setting::getValueByKey('calculate_price'), 2),
            'subtotal'                   => $subtotal,
            'total_tax'                  => $tax,
            'total_discount'             => $total_discount,
            'membership_discount'        => $membership_discount,
            'membership_discount_amount' => $membership_discount_amount,
            'total_price'                => $final_price,
        ]);

        OrderItem::insert($data);
    }

    private function _getProductSku($sku_id, $product_id, $qty): Sku
    {
        $sku = Sku::avaliable()->where('id', $sku_id)->where('product_id', $product_id)->first();

        if ($sku) {
            if ($sku->stock < $qty) {
                throw new Exception("You can only order item quantity $sku->stock.");
            }

            $stock = $sku->stock - $qty;

            $sku->update([
                'stock'        => $stock,
                'stock_status' => $stock == 0 ? 'out_stock' : 'in_stock',
            ]);

            $sku->refresh();

            return $sku;
        } else {
            throw new Exception('Sorry, product was out of stock now.');
        }
    }

    private function _checkPrice(Sku $sku)
    {
        if ($sku->discount_schedule) {
            $discountStartDate = Carbon::parse($sku->discount_start_date);
            $discountEndDate   = Carbon::parse($sku->discount_end_date);
            $now               = Carbon::now();

            $isBetween = $now->between($discountStartDate, $discountEndDate);

            if ($isBetween) {
                return true;
            } else {
                return false;
            }
        } else {
            return $sku->sale_price ? true : false;
        }
    }

    private function _saveCustomerInfo(Request $request, Order $order): void
    {
        $user            = Customer::find($order->customer_id);
        $request['meta'] = [
            'address' => $request->customer['address'],
            'country' => $request->customer['country'],
        ];

        OrderCustomer::create([
            'order_id'    => $order->id,
            'first_name'  => $request->customer['first_name'],
            'last_name'   => $request->customer['last_name'] ?? "XXX",
            'customer_id' => $order->customer_id ?? null,
            'email'       => $request->customer['email'] ?? null,
            'phone'       => $request->customer['phone'] ?? null,
            'address'     => $request->customer['address'] ?? null,
            'country'     => $request->customer['country'] ?? null,
        ]);

        $this->saveMeta($request->meta, $user);
    }

    private function _savePoint(Order $order, Order $oldOrder): void
    {
        $calculation = Setting::getValueByKey('calculate_price') ?? 0;

        $point = round($order->total_price / $calculation, 2);

        $customer = Customer::with(['customerMemberShip'])->find($order->customer_id);

        $total_point = 0;

        $customer->load('customerMemberShip', 'pointTransactions');

        $status = null;

        if (
            ($oldOrder->order_status == Order::ORDER_STATUS['pending'] && $order->order_status == Order::ORDER_STATUS['confirmed']) ||
            ($oldOrder->order_status == Order::ORDER_STATUS['confirmed'] && $order->order_status == Order::ORDER_STATUS['cancel'])
        ):
            $spend = customer_currency($order->total_price);

            switch ($order->order_status) {
                case Order::ORDER_STATUS['confirmed']:
                    $total_point = $customer->customerMemberShip->point_history + $point;
                    $status = 'expand';
                    $translates = TranslateHelper::getTransalateMessage('point_transaction_confirm_msg', $spend, $point);
                    $title = $translates['en']['title'];
                    $body = $translates['en']['body'];
                    break;

                case Order::ORDER_STATUS['cancel']:
                    $total_point = $customer->customerMemberShip->point_history - $point;
                    $status = 'reduce';
                    $translates = TranslateHelper::getTransalateMessage('point_transaction_cancel_msg', $order->order_no, $point);
                    $title = $translates['en']['title'];
                    $body = $translates['en']['body'];
                    break;
            }

            $customer->customerMemberShip()->update([
                'point_history' => $total_point,
                'point'         => $total_point,
            ]);

            $customer->customerMemberShip->refresh();

            $membership = MemberShip::active()
                ->where('min_point', '<=', $customer->customerMemberShip->point_history)
                ->where('max_point', '>=', $customer->customerMemberShip->point_history)
                ->first();

            $customer->customerMemberShip()->update([
                'member_ship_id' => $membership ? $membership->id : null,
                'percentage'     => $membership ? $membership->percentage : null,
            ]);

            $customer->pointTransactions()->create([
                'customer_id'   => $order->customer_id,
                'order_id'      => $order->id,
                'usage_amount'  => $order->total_price,
                'exchange_rate' => $calculation,
                'point'         => $point,
                'status'        => $status,
            ]);

            // $customer->notify(new PointTransaction($title, $body, $translates));

            $tokens = $customer->fcmToken->pluck('token')->toArray();

            $notification = $customer->notifications()->latest()->value('data');

            $data = collect($notification)->except('translates')->toArray();

            // foreach ($tokens as $key => $value) {
            //     (new FcmNotifyService)->sendWithDeviceToken($value, $title, $body, $data);
            // }
        endif;
    }

    private function _sendNotification(Order $order, $type = 'orders'): void
    {
        $customer = Customer::find($order->customer_id);
        $tokens   = $customer->fcmToken->pluck('token')->toArray();
        $order    = Order::with('payment')->findOrFail($order->id);

        $translates = TranslateHelper::getOrderMessage($order);

        // $customer->notify(new OrderShip($order, $translates));

        $notification = $customer->notifications()->latest()->value('data');

        $data = collect($notification)->except('translates')->toArray();

        // foreach ($tokens as $key => $value) {
        //     (new FcmNotifyService)->sendWithDeviceToken($value, $translates['en']['title'], $translates['en']['body'], $data);
        // }

        if (!is_null($order->orderCustomer->email)) {
            OrderMailJob::dispatch($order->orderCustomer, $order);
        }
    }
}
