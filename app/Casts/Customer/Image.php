<?php

namespace App\Casts\Customer;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $existFile = Storage::disk(config('filesystems.default'))->exists("/public/profile/$value");
        if ($existFile && $value) {
            return config('filesystems.default') == 'local' ? asset("storage/profile/$value") : config('constants.image_path') . "/public/profile/$value";
        } else {
            return asset('default.jpeg');
        }
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
