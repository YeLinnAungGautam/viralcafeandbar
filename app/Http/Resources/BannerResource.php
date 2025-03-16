<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'description' => $this->description,
            'status'      => $this->status,
            'banner_type' => $this->banner_type,
            'upload'      => collect($this->upload)->only(['url', 'thumbnail', 'preview', 'file_name']),
            'product_id'  => $this->product_id,
            'category_id' => $this->category_id,
            'product'     => $this->product,
            'category'    => new CategoryResource($this->category),
            'translates'  => new TranslateCollection($this->translates),
            'products'    => $this->products,
        ];

        return $data;
    }
}
