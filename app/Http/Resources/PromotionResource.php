<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'                => $this->id,
            'name'              => $this->name,
            'description'       => $this->description,
            'short_description' => $this->short_description,
            'status'            => $this->status,
            'upload'            => collect($this->upload)->values(),
            'translates'        => new TranslateCollection($this->translates),
            'product_id'        => $this->product_id,
            'category_id'       => $this->category_id,
            'product'           => $this->product,
            'category'          => new CategoryResource($this->category),
            'tags'              => $this->tags,
        ];

        return $data;
    }
}
