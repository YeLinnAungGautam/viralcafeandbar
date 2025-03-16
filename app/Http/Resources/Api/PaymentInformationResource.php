<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'       => $this->id,
            'name'     => $this->name,
            'status'   => $this->status,
            'upload'   => collect($this->upload)->only(['url', 'thumbnail', 'preview', 'file_name']),
            'accounts' => $this->accounts,
        ];

        return $data;
    }
}
