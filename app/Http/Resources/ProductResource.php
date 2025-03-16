<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'description'    => $this->description,
            'type'           => $this->type,
            'skus'           => $this->skus,
            'categories'     => CategoryResource::collection($this->categories),
            'product_status' => $this->product_status,
            'additionals'    => $this->additionals,
            "has_additional" => $this->has_additional,
            "video"          => $this->video,
            'upload'         => collect($this->upload)->map(function ($item) {
                return [
                    'url'       => $item->url,
                    'thumbnail' => $item->thumbnail,
                    'preview'   => $item->preview,
                    'file_name' => $item->file_name,
                ];
            }),
            $this->mergeWhen($this->type != 'simple', [
                'attributes' => $this->attributes,
            ]),
            'active'         => $this->active,
            'translates'     => new TranslateCollection($this->translates),
            'created_at'     => $this->created_at->format('Y-m-d H:i:s'),
        ];

        return $data;
    }
}
