<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TranslateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->collection->groupBy('language.code')
            ->map(function ($item, $key) {
                $data                = $item->pluck('value', 'key');
                $data['language_id'] = $item->value('language_id');
                $data['code'] = $key;
                return $data;
            })->toArray();

        return $data;
    }
}
