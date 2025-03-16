<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reuslt = [
            'id'         => $this->id,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'username'   => $this->username,
            'phone'      => $this->phone,
            'email'      => $this->email,
            'image'      => $this->image,
            'status'     => $this->status,
            'point'      => $this->customerMemberShip?->point ?? 0,
            'name'       => $this->first_name . ' ' . $this->last_name,
            $this->mergeWhen($this->relationLoaded('customerMeta') && $this->customerMeta->isNotEmpty(), [
                'meta' => $this->customerMeta->pluck('meta_value', 'meta_key'),
            ]),
            $this->mergeWhen($this->relationLoaded('customerMemberShip'), [
                'membership' => $this->customerMemberShip,
            ]),
        ];
        return $reuslt;
    }
}
