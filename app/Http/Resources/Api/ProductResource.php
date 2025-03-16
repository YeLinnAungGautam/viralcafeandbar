<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\TranslateCollection;
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
        $result = [
            'id'             => $this->id,
            'name'           => $this->name,
            "slug"           => $this->slug,
            "description"    => $this->description,
            "additionals"    => $this->additionals,
            "excerpt"        => Str::words(strip_tags($this->description), 20),
            "type"           => $this->type,
            "status"         => $this->active,
            "has_additional" => $this->has_additional,
            "video"          => $this->video,
            $this->mergeWhen($this->min_original_price && $this->max_original_price, [
                "min_original_price" => $this->min_original_price,
                "max_original_price" => $this->max_original_price,
            ]),
            $this->mergeWhen($this->min_sale_price && $this->max_sale_price, [
                "min_sale_price" => $this->min_sale_price,
                "max_sale_price" => $this->max_sale_price,
            ]),
            $this->mergeWhen($this->min_discount, [
                "discount" => $this->min_discount,
            ]),
            $this->mergeWhen(isset($this->in_wishlist), [
                'in_wishlist' => $this->in_wishlist,
            ]),
            $this->mergeWhen(isset($this->total_stock), [
                'total_stock' => $this->total_stock,
            ]),
            $this->mergeWhen(
                $this->type != 'simple' && $this->relationLoaded('skus') && $this->skus->isNotEmpty(),
                [
                    "skus" => SkuResource::collection($this->skus),
                ],
            ),
            $this->mergeWhen(
                $this->type == 'simple' && $this->relationLoaded('skus') && $this->skus->isNotEmpty(),
                [
                    'sku' => new SkuResource($this->skus->last()),
                ],
            ),
            $this->mergeWhen(
                $this->relationLoaded('categories') && $this->categories->isNotEmpty(),
                [
                    "categories" => CategoryResource::collection($this->categories),
                ],
            ),
            $this->mergeWhen(
                $this->relationLoaded('translates') && $this->translates->isNotEmpty(),
                [
                    'translates' => new TranslateCollection($this->translates),
                ],
            ),
            'upload'         => collect($this->upload)->map(function ($item) {
                return [
                    'url'       => $item->url,
                    'thumbnail' => $item->thumbnail,
                    'preview'   => $item->preview,
                    'file_name' => $item->file_name,
                    'file_type' => $item->type,
                ];
            }),
            "created_at"     => $this->created_at,
        ];

        return $result;
    }
}
