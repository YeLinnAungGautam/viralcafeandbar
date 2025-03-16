<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => strip_tags($this->description),
            'key'         => $this->key,
            'status'      => $this->status,
            'translates'  => new TranslateCollection($this->translates),
        ];

        return $data;
    }
}
