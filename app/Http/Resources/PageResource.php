<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'page_text' => $this->page_text,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'translates' => new TranslateCollection($this->translates),
        ];

        return $data;
    }
}
