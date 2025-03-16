<?php

namespace App\Http\Resources;

use App\Http\Resources\Api\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'id'              => $this->id,
            'product_name'    => $this->product_name,
            'product_id'      => $this->product_id,
            'sku_name'        => $this->sku_name,
            'sku_id'          => $this->sku_id,
            'order_id'        => $this->order_id,
            'qty'             => $this->qty,
            'unit_price'      => $this->unit_price,
            'total_price'     => $this->total_price,
            'original_price'  => $this->original_price,
            'discount_amount' => $this->discount_amount,
            'tax_price'       => $this->tax_price,
            'product'         => new ProductResource($this->product->load('translates')),
        ];

        return $result;
    }
}
