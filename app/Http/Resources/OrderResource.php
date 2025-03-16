<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'id'                         => $this->id,
            'order_no'                   => $this->order_no,
            'order_status'               => $this->order_status,
            'payment_status'             => $this->payment_status,
            'total_tax'                  => $this->total_tax,
            'subtotal'                   => $this->subtotal,
            'total_price'                => $this->total_price,
            'total_discount'             => $this->total_discount,
            'remark'                     => $this->remark,
            'earn'                       => $this->earn,
            'membership_discount'        => $this->membership_discount,
            'membership_discount_amount' => $this->membership_discount_amount,
            'order_items'                => OrderItemResource::collection($this->orderItems),
            'order_customer'             => $this->orderCustomer,
            'payment_method'             => $this->payment_method,
            'payment'                    => new PaymentResource($this->payment),
            $this->mergeWhen($this->relationLoaded('lastPointTransaction'), [
                'lastPointTransaction' => $this->lastPointTransaction,
            ]),
            // $this->mergeWhen($this->relationLoaded('paymentMethod'), [
            //     'paymentMethod' => $this->paymentMethod,
            // ]),
            'created_at'                 => $this->created_at->format('d M Y'),
        ];
        return $result;
    }
}
