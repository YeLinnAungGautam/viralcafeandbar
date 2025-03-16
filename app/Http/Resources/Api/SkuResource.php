<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'id'                  => $this->id,
            'code'                => $this->code,
            'stock'               => $this->stock,
            'stock_status'        => $this->stock_status,
            'weight'              => $this->weight,
            'discount'            => $this->discount,
            'discount_type'       => $this->discount_type,
            'original_price'      => $this->original_price,
            'sale_price'          => $this->sale_price,
            'discount_schedule'   => $this->discount_schedule,
            'discount_start_date' => $this->discount_start_date,
            'discount_end_date'   => $this->discount_end_date,
            // "preview_image"       => collect($this->upload)->first()['preview'],
            // "images"              => collect($this->upload)->pluck('url'),
        ];

        return $result;
    }
}
