<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $data = [
            'id'         => $this->id,
            'name'       => $this->name,
            'upload'     => collect($this->upload)->only(['url', 'thumbnail', 'preview', 'file_name']),
            'translates' => new TranslateCollection($this->translates),
        ];

        return $data;
    }
}
